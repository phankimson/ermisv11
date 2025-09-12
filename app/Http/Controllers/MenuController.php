<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\HistoryAction;
use App\Http\Model\Menu;
use App\Http\Model\Error;
use App\Http\Model\Software;
use App\Http\Model\Systems;
use App\Http\Resources\DropDownListResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Model\Imports\MenuImport;
use App\Http\Model\Exports\MenuExport;
use App\Classes\Convert;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
  protected $url;
  protected $key;
  protected $menu;
  protected $page_system;
  protected $download;
    public function __construct(Request $request)
   {
       $this->url = $request->segment(3);
       $this->key = "menu";
       $this->menu = Menu::where('code', '=', $this->key)->first();
       $this->page_system = "MAX_COUNT_CHANGE_PAGE";
       $this->download = "Menu.xlsx"; // File download name
   }

   public function show(){
      $type = Software::first();
      //$data = Menu::get_raw_type($type->id);
      $software = collect(DropDownListResource::collection(SoftWare::all()));
      $count = Menu::count();
      $sys_page = Systems::get_systems($this->page_system);
      $paging = $count>$sys_page->value?1:0; 
      return view('manage.'.$this->key,['paging' => $paging, 'key' => $this->key , 'type' => $type->id ,'software' => $software]);
   }
   
  public function data(Request $request){    
    $type = Software::first();
    $ts = $request->input('ts',$type->id);
    $total = Menu::where('type',$ts)->count();
    $sys_page = Systems::get_systems($this->page_system);
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
          $arr = Menu::get_raw_skip_filter_page($skip,$perPage,$orderby,$asc,$filter_sql,$ts);
          $total = Menu::whereRaw($filter_sql)->count();
        }else{
          $arr = Menu::get_raw_skip_page($skip,$perPage,$orderby,$asc,$ts);   
        }   
    $data = collect(['data' => $arr,'total' => $total]);            
    if($data){
      return response()->json($data);
    }else{
      return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
    }
  }

    public function get(Request $request){
      $type = 9;
      try{
        $req = $request->data;
        $data = Menu::get_raw_type($req);
        return response()->json(['status'=>true,'data'=> $data ]);
      }catch(Exception $e){
        // Lưu lỗi
        $err = new Error();
        $err ->create([
          'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
          'user_id' => Auth::id(),
          'menu_id' => $this->menu->id,
          'error' => $e->getMessage().' - Line '.$e->getLine(),
          'url' => $this->url,
          'check' => 0 ]);
        return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage().' - Line '.$e->getLine()]);
      }
   }

   public function save(Request $request){
     $type = 0;
     try{
      DB::beginTransaction();
      $permission = $request->session()->get('per');
      $arr = json_decode($request->data);
      $validator = Validator::make(collect($arr)->toArray(),[
             'code' => ['required','max:100'],
             'name' => 'required',
             'name_en' => 'required',
         ]);
      if($validator->passes()){
      $code_check = Menu::WhereCheck1('code',$arr->code,'link',$arr->link,'id',$arr->id)->first();
      if($code_check == null){
      if($permission['a'] == true && !$arr->id ){
        $type = 2;
        $data = new Menu();
        $data->type = $arr->type;
        $data->parent_id = $arr->parent_id;
        $data->code = $arr->code;
        $data->name = $arr->name;
        $data->name_en = $arr->name_en;
        $data->icon = $arr->icon;
        $data->link = $arr->link;
        $data->group = $arr->group;
        $data->position = Convert::intDefaultformat($arr->position);
        $data->active = $arr->active;
        $data->save();

        // Lưu lịch sử ---- NOT EDIT
        $h = new HistoryAction();
        $h ->create([
          'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
          'user' => Auth::id(),
          'menu' => $this->menu->id,
          'url' => $this->url,
          'dataz' => \json_encode($data)]);
        /////////////////////////////
        // Lấy ID và và phân loại Thêm
        $arr->id = $data->id;
        $arr->t = $type;
        DB::commit();  
        broadcast(new \App\Events\DataSend($arr));
        return response()->json(['status'=>true,'message'=> trans('messages.add_success')]);
      }else if($permission['e'] == true && $arr->id){
        $type = 3;
        $data = Menu::find($arr->id);
        if(!$data){
          return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
        }
        // Lưu lịch sử ---- NOT EDIT
        $h = new HistoryAction();
        $h ->create([
          'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
          'user' => Auth::id(),
          'menu' => $this->menu->id,
          'url' => $this->url,
          'dataz' => \json_encode($data)]);
        /////////////////////////////
        $data->type = $arr->type;
        $data->parent_id = $arr->parent_id;
        $data->code = $arr->code;
        $data->name = $arr->name;
        $data->name_en = $arr->name_en;
        $data->icon = $arr->icon;
        $data->link = $arr->link;
        $data->group = $arr->group;
        $data->position = Convert::intDefaultformat($arr->position);
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
           return response()->json(['status'=>false,'message'=> trans('messages.code_url_is_already')]);
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
         'error' => $e->getMessage().' - Line '.$e->getLine(),
         'url' => $this->url,
         'check' => 0 ]);
       return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage().' - Line '.$e->getLine()]);
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
             $data = Menu::find($arr->id);
             if(!$data){
              return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
            }
             // Lưu lịch sử
             $h = new HistoryAction();
             $h ->create([
               'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
               'user' => Auth::id(),
               'menu' => $this->menu->id,
               'url' => $this->url,
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
           'error' => $e->getMessage().' - Line '.$e->getLine(),
           'url' => $this->url,
           'check' => 0 ]);
         return response()->json(['status'=>false,'message'=> trans('messages.delete_fail').' '.$e->getMessage().' - Line '.$e->getLine()]);
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
        $import = new MenuImport;
        Excel::import($import, $file);
        // Lấy lại dữ liệu
       
        $merged = collect($rs)->push($import->getData());
        //dump($merged);
      // Lưu lịch sử
      $h = new HistoryAction();
      $h ->create([
        'type' => $type, // Add : 2 , Edit : 3 , Delete : 4, Import : 5
        'user' => Auth::id(),
        'menu' => $this->menu->id,
        'url' => $this->url,
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
        'error' => $e->getMessage().' - Line '.$e->getLine(),
        'url' => $this->url,
        'check' => 0 ]);
      return response()->json(['status'=>false,'message'=> trans('messages.failed_import').' '.$e->getMessage().' - Line '.$e->getLine()]);
    }
  }

  public function export(Request $request) {
    $type = 6;
    try{
        $arr = $request->data;
        $page = $request->page;
        //return (new HistoryActionExport($arr))->download('HistoryActionExportErmis.xlsx');
        //$myFile = Excel::download(new HistoryActionExport($arr), 'HistoryActionExportErmis.xlsx');
        $myFile = Excel::raw(new MenuExport($arr,$page), \Maatwebsite\Excel\Excel::XLSX);
        $response =  array(
          'status' =>true,
          'name' => "MenuExportErmis", //no extention needed
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
        'error' => $e->getMessage().' - Line '.$e->getLine(),
        'url' => $this->url,
        'check' => 0 ]);
      return response()->json(['status'=>false,'message'=> trans('messages.failed_export').' '.$e->getMessage().' - Line '.$e->getLine()]);
    }
  }

}
