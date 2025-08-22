<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\HistoryAction;
use App\Http\Model\Menu;
use App\Http\Model\Software;
use App\Http\Model\Systems;
use App\Http\Model\Error;
use Illuminate\Support\Facades\Storage;
use App\Http\Model\Imports\SoftwareImport;
use App\Http\Model\Exports\SoftwareExport;
use App\Classes\Convert;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Hashids\Hashids;
use Exception;
use Illuminate\Support\Facades\DB;

class SoftwareController extends Controller
{
  protected $url;
  protected $key;
  protected $menu;
  protected $page_system;
  protected $path;
  protected $length_hash;
  protected $download;
  public function __construct(Request $request)
 {
     $this->url = $request->segment(3);
     $this->key = "software";
     $this->path = "PATH_UPLOAD_SOFTWARE";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->length_hash = 50;
     $this->page_system = "MAX_COUNT_CHANGE_PAGE";
     $this->download = "Software.xlsx"; // File download name
 }

  public function show(){
    //$data = Software::get_raw();
    $count = Software::count();
    $sys_page = Systems::get_systems($this->page_system);
    $paging = $count>$sys_page->value?1:0; 
    return view('manage.'.$this->key,['paging' => $paging, 'key' => $this->key ]);
  } 
  
  public function data(Request $request){    
    $total = Software::count();
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
          $arr = Software::get_raw_skip_filter_page($skip,$perPage,$orderby,$asc,$filter_sql);
          $total = Software::whereRaw($filter_sql)->count();
        }else{
          $arr = Software::get_raw_skip_page($skip,$perPage,$orderby,$asc);   
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
    $arr = json_decode($request->data);
    $validator = Validator::make(collect($arr)->toArray(),[
            'name' => 'required',
            'url' => 'required',
        ]);

     if($validator->passes()){
       $code_check = Software::WhereCheck('url',$arr->url,'id',$arr->id)->first();
     if($code_check == null){
     $hashids = new Hashids('',$this->length_hash);
     if($permission['a'] == true && !$arr->id ){
       $type = 2;
       $data = new Software();
       $data->name = $arr->name;
       $data->name_en = $arr->name_en;
       $data->url = $arr->url;
       $data->note = $arr->note;
       $data->database_temp = $arr->database_temp;
       $data->username_temp = $arr->username_temp;
       $data->password_temp = $hashids->encode($arr->password_temp);
       $data->active = $arr->active;
       $data->save();

       // Lưu lịch sử
       $h = new HistoryAction();
       $h ->create([
         'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
         'user' => Auth::id(),
         'menu' => $this->menu->id,
         'url' => $this->url,
         'dataz' => \json_encode($data)]);

       // Lấy ID và và phân loại Thêm
       $arr->id = $data->id;
       $arr->t = $type;
       //Lưu lại pass
       $arr->password = $data->password;

       // Lưu ảnh thêm
       if($request->hasFile('files')) {
         $files = $request->file('files');
         $filename = $files->getClientOriginalName();
         $sys = Systems::get_systems($this->path);
         $path = public_path().'/'.$sys->value . $arr->id;
         $pathname = $sys->value . $arr->id.'/'.$filename;
         if(!File::isDirectory($path)){
         File::makeDirectory($path, 0777, true, true);
         }
         $upload_success = $files->move($path, $filename);
         // Lưu lại hình ảnh
         $data = Software::find($arr->id);
         $data->image = $pathname;
         $data->save();
         //Lưu ảnh lại array
         $arr->image = $pathname;
       }
       //
       DB::commit(); 
       broadcast(new \App\Events\DataSend($arr));
       return response()->json(['status'=>true,'message'=> trans('messages.add_success')]);
     }else if($permission['e'] == true && $arr->id){
       $type = 3;
       $data = Software::find($arr->id);
       // Lưu lịch sử
       $h = new HistoryAction();
       $h ->create([
         'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
         'user' => Auth::id(),
         'menu' => $this->menu->id,
         'url' => $this->url,
         'dataz' => \json_encode($data)]);
      //
      $old_password = $data->password_temp;
      $password_new =   $hashids->encode($arr->password_temp);
      if ($password_new != $old_password) {
            $data->password_temp = $password_new;
       }
       $data->name = $arr->name;
       $data->name_en = $arr->name_en;
       $data->url = $arr->url;
       $data->database_temp = $arr->database_temp;
       $data->username_temp = $arr->username_temp;
       $data->note = $arr->note;
       $data->active = $arr->active;
       $data->save();
       //Lưu lại pass
       $arr->password_temp = $data->password_temp;
       // Phân loại Sửa
       $arr->t = $type;

       // Lưu ảnh sửa
       if($request->hasFile('files')) {
         //Xóa ảnh cũ
         if($data->image && File::exists(public_path($data->image))){
            File::delete(public_path($data->image));
         };

         $files = $request->file('files');
         $filename = $files->getClientOriginalName();
         $sys = Systems::get_systems($this->path);
         $path = public_path().'/'.$sys->value . $arr->id;
         $pathname = $sys->value . $arr->id.'/'.$filename;
         if(!File::isDirectory($path)){
         File::makeDirectory($path, 0777, true, true);
         }
         $upload_success = $files->move($path, $filename);
         // Lưu lại hình ảnh
         $data = Software::find($arr->id);
         $data->image = $pathname;
         $data->save();
         //Lưu ảnh lại array
         $arr->image = $pathname;
       }
       DB::commit(); 
       broadcast(new \App\Events\DataSend($arr));
       return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
       }else{
        return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission')]);
       }
     }else{
          return response()->json(['status'=>false,'message'=> trans('messages.url_is_already')]);
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
   $type = 4 ;
      try{
        DB::beginTransaction();
        $permission = $request->session()->get('per');
        $arr = json_decode($request->data);
        if($arr){
          if($permission['d'] == true){
            $data = Software::find($arr->id);
            // Lưu lịch sử
            $h = new HistoryAction();
            $h ->create([
            'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
            'user' => Auth::id(),
            'menu' => $this->menu->id,
            'url' => $this->url,
            'dataz' => \json_encode($data)]);
            //

            //Xóa ảnh cũ
            if($data->image && File::exists(public_path($data->image))){
               File::delete(public_path($data->image));
            };

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
       $import = new SoftwareImport;
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
       $myFile = Excel::raw(new SoftwareExport($arr,$page), \Maatwebsite\Excel\Excel::XLSX);
       $response =  array(
         'status' =>true,
         'name' => "SoftwareExportErmis", //no extention needed
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
