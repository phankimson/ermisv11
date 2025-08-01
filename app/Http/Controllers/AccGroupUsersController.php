<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\Menu;
use App\Http\Model\AccGroupUsers;
use App\Http\Model\Error;
use App\Http\Model\AccSystems;
use Illuminate\Support\Facades\Storage;
use App\Http\Model\Imports\AccGroupUsersImport;
use App\Http\Model\Exports\AccGroupUsersExport;
use App\Classes\Convert;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\DB;


class AccGroupUsersController extends Controller
{
  protected $url;
  protected $key;
  protected $menu;
  protected $page_system;
  protected $download;
  public function __construct(Request $request)
  {
     $this->url =  $request->segment(3);
     $this->key = "group-users";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->page_system = "MAX_COUNT_CHANGE_PAGE";
     $this->download = 'AccGroupUsers.xlsx';
 }

  public function show(Request $request){
    $com = $request->session()->get('com');
    //$data = AccGroupUsers::get_raw($com->id);
    $count = AccGroupUsers::where('company_id',$com->id)->count();
    $sys_page = AccSystems::get_systems($this->page_system);
    $paging = $count>$sys_page->value?1:0;   
    return view('acc.'.str_replace("-", "_", $this->key),['paging' => $paging, 'key' => $this->key ]);
  }

  public function data(Request $request){  
    $com = $request->session()->get('com'); 
    $total = AccGroupUsers::where('company_id',$com->id)->count();
    $sys_page = AccSystems::get_systems($this->page_system);    
    $perPage = $request->input('$top',$sys_page->value);
    $skip = $request->input('$skip',0);
    $orderby =   $request->input('$orderby','created_at desc');
    $filter =   $request->input('$filter');
    $asc  = 'desc';
        if (!str_contains($orderby, 'desc')) { 
          $asc = 'asc';
        }else{
          $orderby = explode(' ', $orderby)[0];
        };
        if($filter){
          $filter_sql = Convert::filterRow($filter);
          $arr = AccGroupUsers::get_raw_skip_filter_page($skip,$perPage,$orderby,$asc,$filter_sql,$com->id);
          $total = AccGroupUsers::whereRaw($filter_sql)->where('company_id',$com->id)->count();
        }else{
          $arr = AccGroupUsers::get_raw_skip_page($skip,$perPage,$orderby,$asc,$com->id); 
        } 
    $data = collect(['data' => $arr,'total' => $total]);              
    if($data){
      return response()->json($data);
    }else{
      return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
    }
  }

  public function save(Request $request){
    $type = 0;
    try{
      DB::beginTransaction();
      $permission = $request->session()->get('per');
      $com = $request->session()->get('com');
      $arr = json_decode($request->data);
      $validator = Validator::make(collect($arr)->toArray(),[
            'code' => ['required','max:50'],
            'name' => 'required',
        ]);
    if($validator->passes()){
      $code_check = AccGroupUsers::WhereCheck('code',$arr->code,'company_id',$com,'id',$arr->id)->first();
      if($code_check == null){
     if($permission['a'] == true && !$arr->id ){
       $type = 2;
       $data = new AccGroupUsers();
       $data->company_id = $com->id;
       $data->code = $arr->code;
       $data->name = $arr->name;
       $data->active = $arr->active;
       $data->save();

       // Lưu lịch sử
       $h = new AccHistoryAction();
       $h ->create([
         'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
         'user' => Auth::id(),
         'menu' => $this->menu->id,
          'url'  => $this->url,
         'dataz' => \json_encode($data)]);

       // Lấy ID và và phân loại Thêm
       $arr->id = $data->id;
       $arr->t = $type;
       DB::commit();
       broadcast(new \App\Events\DataSend($arr));
       return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
     }else if($permission['e'] == true && $arr->id){
       $type = 3;
       $data = AccGroupUsers::find($arr->id);
       // Lưu lịch sử
       $h = new AccHistoryAction();
       $h ->create([
         'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
         'user' => Auth::id(),
         'menu' => $this->menu->id,
          'url'  => $this->url,
         'dataz' => \json_encode($data)]);
      //
       $data->company_id = $com->id;
       $data->code = $arr->code;
       $data->name = $arr->name;
       $data->active = $arr->active;
       $data->save();
       // Phân loại Sửa
       $arr->t = $type;
       DB::commit();
       broadcast(new \App\Events\DataSend($arr));
       return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
       }else{
        return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission')]);
       }
      }else{
        return response()->json(['status'=>false,'message'=> trans('messages.code_is_already')]);
      }
     }else{
      DB::rollBack();
       return response()->json(['status'=>false,'error'=>$validator->getMessageBag()->toArray() ,'message'=>trans('messages.error')]);
     }
    }catch(Exception $e){
      DB::rollBack();
      // Lưu lỗi
      $err = new Error();
      $err ->create([
        'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
        'user_id' => Auth::id(),
        'menu_id' => $this->menu->id,
        'error' => $e->getMessage(),
         'url'  => $this->url,
        'check' => 0 ]);
      return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
    }
 }

 public function delete(Request $request) {
   $type = 4;
      try{
        DB::beginTransaction();
        $permission = $request->session()->get('per');
        $arr = json_decode($request->data);
        if($arr){
          if($permission['d'] == true){
            $data = AccGroupUsers::find($arr->id);
            // Lưu lịch sử
            $h = new AccHistoryAction();
            $h ->create([
            'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
            'user' => Auth::id(),
            'menu' => $this->menu->id,
             'url'  => $this->url,
            'dataz' => \json_encode($data)]);
            //
            $data->delete();
            DB::commit();
            broadcast(new \App\Events\DataSend($arr));
            return response()->json(['status'=>true,'message'=> trans('messages.delete_success')]);
          }else{
            return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission_delete')]);
          }
       }else{
         return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
       }
      }catch(Exception $e){
        DB::rollBack();
        // Lưu lỗi
        $err = new Error();
        $err ->create([
          'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
          'user_id' => Auth::id(),
          'menu_id' => $this->menu->id,
          'error' => $e->getMessage(),
           'url'  => $this->url,
          'check' => 0 ]);
        return response()->json(['status'=>false,'message'=> trans('messages.delete_fail').' '.$e->getMessage()]);
      }
 }

 public function DownloadExcel(){
   return Storage::download('public/downloadFile/'.$this->download);
 }

 public function import(Request $request) {  
   $type = 5;
   try{
    DB::beginTransaction();
   $permission = $request->session()->get('per');
   if($permission['a'] && $request->hasFile('file')){
     if($request->file->getClientOriginalName() == $this->download){
     //Check
     $request->validate([
         'file' => 'required|mimeTypes:'.
               'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,'.
               'application/vnd.ms-excel',
     ]);
       $rs = json_decode($request->data);

       $file = $request->file;
       // Import dữ liệu
       $import = new AccGroupUsersImport;
       Excel::import($import, $file);
       // Lấy lại dữ liệu
       
       $merged = collect($rs)->push($import->getData());
       //dump($merged);
     // Lưu lịch sử
     $h = new AccHistoryAction();
     $h ->create([
       'type' => $type, // Add : 2 , Edit : 3 , Delete : 4, Import : 5
       'user' => Auth::id(),
       'menu' => $this->menu->id,
        'url'  => $this->url,
       'dataz' => \json_encode($merged)]);
     //
     //Storage::delete($savePath.$filename);
     DB::commit();
     broadcast(new \App\Events\DataSendCollection($merged));
     return response()->json(['status'=>true,'message'=> trans('messages.success_import')]);
    }else{
    return response()->json(['status'=>false,'message'=> trans('messages.incorrect_file')]);
    } 
     }else{
       return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
     }
   }catch(Exception $e){
    DB::rollBack();
     // Lưu lỗi
     $err = new Error();
     $err ->create([
       'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
       'user_id' => Auth::id(),
       'menu_id' => $this->menu->id,
       'error' => $e->getMessage(),
        'url'  => $this->url,
       'check' => 0 ]);
     return response()->json(['status'=>false,'message'=> trans('messages.failed_import').' '.$e->getMessage()]);
   }
 }

 public function export(Request $request) {
   $type = 6;
   try{
       $arr = $request->data;
       $com = $request->session()->get('com');
       $page = $request->page;
       //return (new HistoryActionExport($arr))->download('HistoryActionExportErmis.xlsx');
       //$myFile = Excel::download(new HistoryActionExport($arr), 'HistoryActionExportErmis.xlsx');
       $myFile = Excel::raw(new AccGroupUsersExport($arr,$com->id,$page), \Maatwebsite\Excel\Excel::XLSX);
       $response =  array(
         'status' =>true,
         'name' => "GroupUsersExportErmis", //no extention needed
         'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($myFile) //mime type of used format
      );
      return response()->json($response);
   }catch(Exception $e){
     // Lưu lỗi
     $err = new Error();
     $err ->create([
       'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
       'user_id' => Auth::id(),
       'menu_id' => $this->menu->id,
       'error' => $e->getMessage(),
        'url'  => $this->url,
       'check' => 0 ]);
     return response()->json(['status'=>false,'message'=> trans('messages.failed_export').' '.$e->getMessage()]);
   }
 }

}
