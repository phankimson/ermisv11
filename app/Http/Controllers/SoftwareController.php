<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\HistoryAction;
use App\Http\Model\User;
use App\Http\Model\Menu;
use App\Http\Model\Software;
use App\Http\Model\Systems;
use App\Http\Model\Error;
use Illuminate\Support\Facades\Storage;
use App\Http\Model\Imports\SoftwareImport;
use App\Http\Model\Exports\SoftwareExport;
use App\Classes\Convert;
use Excel;
use File;
use Hashids\Hashids;

class SoftwareController extends Controller
{
  public function __construct(Request $request)
 {
     $this->url = $request->segment(3);
     $this->key = "software";
     $this->path = "PATH_UPLOAD_SOFTWARE";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->length_hash = 50;
 }

  public function show(){
    $data = Software::get_raw();
    return view('manage.software',['data' => $data, 'key' => $this->key ]);
  }

  public function save(Request $request){
    $type = 0;
    try{
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

       broadcast(new \App\Events\DataSend($arr));
       return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
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
       //
       broadcast(new \App\Events\DataSend($arr));
       return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
       }else{
        return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission')]);
       }
     }else{
          return response()->json(['status'=>false,'message'=> trans('messages.url_is_already')]);
     }
     }else{
        return response()->json(['status'=>false,'error'=>$validator->getMessageBag()->toArray() ,'message'=>trans('messages.error')]);
     }
    }catch(Exception $e){
      // Lưu lỗi
      $err = new Error();
      $err ->create([
        'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
        'user_id' => Auth::id(),
        'menu_id' => $this->menu->id,
        'error' => $e->getMessage(),
        'url' => $this->url,
        'check' => 0 ]);
      return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
    }
 }

 public function delete(Request $request) {
   $type = 4 ;
      try{
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
            broadcast(new \App\Events\DataSend($arr));
            return response()->json(['status'=>true,'message'=> trans('messages.delete_success')]);
          }else{
            return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission_delete')]);
          }
       }else{
         return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
       }
      }catch(Exception $e){
        // Lưu lỗi
        $err = new Error();
        $err ->create([
          'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
          'user_id' => Auth::id(),
          'menu_id' => $this->menu->id,
          'error' => $e->getMessage(),
          'url' => $this->url,
          'check' => 0 ]);
        return response()->json(['status'=>false,'message'=> trans('messages.delete_fail').' '.$e->getMessage()]);
      }
 }

 public function DownloadExcel(Request $request){
   return Storage::download('public/downloadFile/Software.xlsx');
 }

 public function import(Request $request) {
   $type = 5;
   try{
   $permission = $request->session()->get('per');
   if($permission['a'] && $request->hasFile('file')){
     //Check
     $request->validate([
         'file' => 'required|mimeTypes:'.
               'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,'.
               'application/vnd.ms-excel',
     ]);
       $rs = json_decode($request->data);

       $file = $request->file;
       // Import dữ liệu
       Excel::import(new SoftwareImport, $file);
       // Lấy lại dữ liệu
       $array = Software::get_raw();

       // Import dữ liệu bằng collection
       //$results = Excel::toCollection(new HistoryActionImport, $file);
       //dump($results);
       //foreach($results[0] as $item){
       //  $data = new HistoryAction();
       //  $data->type = $item->get('type');
       //  $data->user = $item->get('user');
       //  $data->menu = $item->get('menu');
       //  $data->dataz = $item->get('dataz');
       //  $data->save();
       //  $arr->push($data);
       //}
       $merged = collect($rs)->push($array);
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
     broadcast(new \App\Events\DataSendCollection($merged));
     return response()->json(['status'=>true,'message'=> trans('messages.success_import')]);
     }else{
       return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
     }
   }catch(Exception $e){
     // Lưu lỗi
     $err = new Error();
     $err ->create([
       'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
       'user_id' => Auth::id(),
       'menu_id' => $this->menu->id,
       'error' => $e->getMessage(),
       'url' => $this->url,
       'check' => 0 ]);
     return response()->json(['status'=>false,'message'=> trans('messages.failed_import').' '.$e->getMessage()]);
   }
 }

 public function export(Request $request) {
   $type = 6;
   try{
       $arr = $request->data;
       //return (new HistoryActionExport($arr))->download('HistoryActionExportErmis.xlsx');
       //$myFile = Excel::download(new HistoryActionExport($arr), 'HistoryActionExportErmis.xlsx');
       $myFile = Excel::raw(new SoftwareExport($arr), \Maatwebsite\Excel\Excel::XLSX);
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
       'error' => $e->getMessage(),
       'url' => $this->url,
       'check' => 0 ]);
     return response()->json(['status'=>false,'message'=> trans('messages.failed_import').' '.$e->getMessage()]);
   }
 }

}
