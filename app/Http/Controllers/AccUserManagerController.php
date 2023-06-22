<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\AccUser;
use App\Http\Model\Menu;
use App\Http\Model\Company;
use App\Http\Model\Country;
use App\Http\Model\AccGroupUsers;
use App\Http\Model\Systems;
use App\Http\Model\Error;
use Illuminate\Support\Facades\Storage;
use App\Http\Model\Imports\AccUserImport;
use App\Http\Model\Exports\AccUserExport;
use App\Classes\Convert;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Excel;
use File;

class AccUserManagerController extends Controller
{
  public function __construct(Request $request)
 {
     $this->url =  $request->segment(3);
     $this->key = "users";
     $this->path = "PATH_UPLOAD_AVATAR";
     $this->menu = Menu::where('code', '=', $this->key)->first();
 }

  public function show(Request $request){
    $mysql2 = $request->session()->get('mysql2');
    config(['database.connections.mysql2' => $mysql2]);
    $com = $request->session()->get('com');
    $data = AccUser::company($com->id)->get()->makeVisible(['active_code','password']);
    $group_user = AccGroupUsers::activeCompany($com->id)->active()->get();
    $country = Country::all();
    $prefix_username = substr(Crypt::encryptString($com->id),0,5);
    return view('acc.users',['data' => $data, 'key' => $this->key , 'group_user'=>$group_user ,'country' =>$country ,'prefix_username'=>$prefix_username]);
  }

  public function save(Request $request){
    $mysql2 = $request->session()->get('mysql2');
    config(['database.connections.mysql2' => $mysql2]);
    $type = 0 ;
    try{
  $com = $request->session()->get('com');
  $prefix_username = substr(Crypt::encryptString($com->id),0,5);
  $permission = $request->session()->get('per');
  $arr = json_decode($request->data);
    if($arr){
     if($permission['a'] == true && !$arr->id ){
       $check_user = AccUser::get_user($prefix_username.'_'.$arr->username);
       if(!$check_user){
       $type = 2;
       $data = new AccUser();
       $data->username = $prefix_username.'_'.$arr->username;
       $data->fullname = $arr->fullname;
       $data->firstname = $arr->firstname;
       $data->lastname = $arr->lastname;
       $data->password = Hash::make($arr->password);
       $data->identity_card = $arr->identity_card;
       $data->avatar = 'addon/img/avatar.png';
       $data->phone = $arr->phone;
       $data->email = Convert::StringDefaultformat($arr->email);
       $data->birthday = Convert::dateDefaultNull($arr->birthday);
       $data->address = $arr->address;
       $data->city = $arr->city;
       $data->jobs = $arr->jobs;
       $data->country = $arr->country;
       $data->role = 2;
       $data->group_users_id = $arr->group_users_id;
       $data->barcode = $arr->barcode;
       $data->stock_default = $arr->stock_default;
       $data->company_default = $com->id;
       $data->about = $arr->about;
       $data->api_token = Str::random(60);
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

       // Lấy ID và và phân loại Thêm. Cập nhật lại username
       $arr->id = $data->id;
       $arr->username = $prefix_username.'_'.$arr->username;
       $arr->t = $type;

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
         $data = AccUser::find($arr->id);
         $data->avatar = $pathname;
         $data->save();
         //Lưu ảnh lại array
         $arr->avatar = $pathname;
       }
       //

       broadcast(new \App\Events\DataSend($arr));
       return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
       }else{
          return response()->json(['status'=>false,'message'=> trans('messages.username_is_already_taken')]);
       }
     }else if($permission['e'] == true && $arr->id){
       $check_user = AccUser::get_user_not_id($prefix_username.'_'.$arr->username,$arr->id);
       if($check_user->count() == 0){
       $type = 3;
       $data = AccUser::find($arr->id);
       // Lưu lịch sử
       $h = new AccHistoryAction();
       $h ->create([
         'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
         'user' => Auth::id(),
         'menu' => $this->menu->id,
         'url'  => $this->url,
         'dataz' => \json_encode($data)]);
      //
        $old_password = $data->password;
        if ($arr->password != $old_password ) {
              $data->password = Hash::make($arr->password);
         }
        $data->username = $prefix_username.'_'.$arr->username;
        $data->fullname = $arr->fullname;
        $data->firstname = $arr->firstname;
        $data->lastname = $arr->lastname;
        $data->identity_card = $arr->identity_card;
        $data->birthday = Convert::dateDefaultNull($arr->birthday);
        $data->phone = $arr->phone;
        $data->email = Convert::StringDefaultformat($arr->email);
        $data->address = $arr->address;
        $data->city = $arr->city;
        $data->jobs = $arr->jobs;
        $data->country = $arr->country;
        $data->group_users_id = $arr->group_users_id;
        $data->barcode = $arr->barcode;
        $data->stock_default = $arr->stock_default;
        $data->about = $arr->about;
        $data->active = $arr->active;
        $data->save();
         // Phân loại Sửa
         $arr->t = $type;
         $arr->username = $prefix_username.'_'.$arr->username;
       // Lưu ảnh sửa
       if($request->hasFile('files')) {
         //Xóa ảnh cũ
         if(File::exists(public_path($data->avatar)) && $data->avatar != 'addon/img/avatar.png'){
            File::delete(public_path($data->avatar));
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
         $data = AccUser::find($arr->id);
         $data->avatar = $pathname;
         $data->save();
         //Lưu ảnh lại array
         $arr->avatar = $pathname;
       }
       //

       broadcast(new \App\Events\DataSend($arr));
       return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
      
     }else{
        return response()->json(['status'=>false,'message'=> trans('messages.username_is_already_taken')]);
     }
       }else{
        return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission')]);
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
        'url'  => $this->url,
        'check' => 0 ]);
      return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
    }
 }

 public function delete(Request $request) {
  $mysql2 = $request->session()->get('mysql2');
  config(['database.connections.mysql2' => $mysql2]);
      $type = 4;
      try{
        $permission = $request->session()->get('per');
        $arr = json_decode($request->data);
        if($arr){
          if($permission['d'] == true){
            $data = User::find($arr->id);
            // Lưu lịch sử
            $h = new AccHistoryAction();
            $h ->create([
            'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
            'user' => Auth::id(),
            'menu' => $this->menu->id,
            'url'  => $this->url,
            'dataz' => \json_encode($data)]);
            //

            //Xóa ảnh cũ
            if(File::exists(public_path($data->avatar)) && $data->avatar != 'addon/img/avatar.png'){
               File::delete(public_path($data->avatar));
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
          'type' => $type, // Add : 2 , Edit : 3 , Delete : 4 , Import : 5 , Export : 6, Timeline : 7 , Loadmore : 8, Load : 9
          'user_id' => Auth::id(),
          'menu_id' => $this->menu->id,
          'error' => $e->getMessage(),
          'url'  => $this->url,
          'check' => 0 ]);
        return response()->json(['status'=>false,'message'=> trans('messages.delete_fail').' '.$e->getMessage()]);
      }
 }

 public function DownloadExcel(Request $request){
   return Storage::download('public/downloadFile/AccUser.xlsx');
 }

 public function import(Request $request) {
  $mysql2 = $request->session()->get('mysql2');
  config(['database.connections.mysql2' => $mysql2]);
   $type = 5;
   try{
  $com = $request->session()->get('com');
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
       Excel::import(new AccUserImport, $file);
       // Lấy lại dữ liệu
       $array = AccUser::company($com->id)->get()->makeVisible(['active_code','password']);

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
     $h = new AccHistoryAction();
     $h ->create([
       'type' => $type, // Add : 2 , Edit : 3 , Delete : 4, Import : 5
       'user' => Auth::id(),
       'menu' => $this->menu->id,
       'url'  => $this->url,
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
       'url'  => $this->url,
       'check' => 0 ]);
     return response()->json(['status'=>false,'message'=> trans('messages.failed_import').' '.$e->getMessage()]);
   }
 }

 public function export(Request $request) {
  $mysql2 = $request->session()->get('mysql2');
  config(['database.connections.mysql2' => $mysql2]);
    $type = 6;
   try{
       $com = $request->session()->get('com');
       $arr = $request->data;
       //return (new HistoryActionExport($arr))->download('HistoryActionExportErmis.xlsx');
       //$myFile = Excel::download(new HistoryActionExport($arr), 'HistoryActionExportErmis.xlsx');
       $myFile = Excel::raw(new AccUserExport($arr,$com->id), \Maatwebsite\Excel\Excel::XLSX);
       $response =  array(
         'status' =>true,
         'name' => "UserExportErmis", //no extention needed
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
     return response()->json(['status'=>false,'message'=> trans('messages.failed_import').' '.$e->getMessage()]);
   }
 }

}
