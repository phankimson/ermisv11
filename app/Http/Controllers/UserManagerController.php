<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Model\HistoryAction;
use App\Http\Model\User;
use App\Http\Model\Menu;
use App\Http\Model\Systems;
use App\Http\Model\Error;
use Illuminate\Support\Facades\Storage;
use App\Http\Model\Imports\UserImport;
use App\Http\Model\Exports\UserExport;
use App\Classes\Convert;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Exception;
use Illuminate\Support\Facades\DB;

class UserManagerController extends Controller
{
  protected $url;
  protected $key;
  protected $menu;
  protected $page_system;
  protected $path;
  protected $download;
  public function __construct(Request $request)
 {
     $this->url =  $request->segment(3);
     $this->key = "users";
     $this->path = "PATH_UPLOAD_AVATAR";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->page_system = "MAX_COUNT_CHANGE_PAGE";
     $this->download = "User.xlsx"; // File download name
 }

  public function show(){
    //$data = User::all()->makeVisible(['active_code','password']);
    //$group_user = GroupUsers::active()->get();
    //$company_default = collect(DropDownListResource::collection(Company::active()->get()));
    //$country = Country::all();
    $count = User::count();
    $sys_page = Systems::get_systems($this->page_system);
    $paging = $count>$sys_page->value?1:0; 
    return view('manage.'.$this->key,['paging' => $paging, 'key' => $this->key ]);
  }

  
  public function data(Request $request){    
    $total = User::count();
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
          $arr = User::get_raw_skip_filter_page($skip,$perPage,$orderby,$asc,$filter_sql);
          $total = User::whereRaw($filter_sql)->count();
        }else{
          $arr = User::get_raw_skip_page($skip,$perPage,$orderby,$asc);    
        }    
    $data = collect(['data' => $arr,'total' => $total]);            
    if($data){
      return response()->json($data);
    }else{
      return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
    }
  }

  public function save(Request $request){
    $type = 0 ;
    try{
      DB::beginTransaction();
      $permission = $request->session()->get('per');
      $arr = json_decode($request->data);
      $check_user = User::WhereCheck('username',$arr->username,'id',$arr->id)->first();
  if(!$check_user){
    if($arr){
     if($permission['a'] == true && !$arr->id ){
       $type = 2;
       $data = new User();
       $data->username = $arr->username;
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
       $data->role = Convert::intDefaultNumberformat($arr->role,1);
       $data->group_users_id = $arr->group_users_id;
       $data->active_code = $arr->active_code;
       $data->barcode = $arr->barcode;
       $data->stock_default = $arr->stock_default;
       $data->company_default = $arr->company_default;
       $data->about = $arr->about;
       $data->api_token = Str::random(60);
       $data->active = $arr->active;
       $data->save();

       // Lưu lịch sử
       $h = new HistoryAction();
       $h ->create([
         'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
         'user' => Auth::id(),
         'menu' => $this->menu->id,
         'url'  => $this->url,
         'dataz' => \json_encode($data)]);

       // Lấy ID và và phân loại Thêm
       $arr->id = $data->id;
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
         $data = User::find($arr->id);
         if($data){
          $data->avatar = $pathname;
          $data->save();
         }
         
         //Lưu ảnh lại array
         $arr->avatar = $pathname;
       }
       //
       DB::commit(); 
       broadcast(new \App\Events\DataSend($arr));
       return response()->json(['status'=>true,'message'=> trans('messages.add_success')]);
     }else if($permission['e'] == true && $arr->id){
       $type = 3;
       $data = User::find($arr->id);
         if(!$data){
          return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
        }
       // Lưu lịch sử
       $h = new HistoryAction();
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
        $data->username = $arr->username;
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
        $data->role = Convert::intDefaultNumberformat($arr->role,1);
        $data->group_users_id = $arr->group_users_id;
        $data->active_code = $arr->active_code;
        $data->barcode = $arr->barcode;
        $data->stock_default = $arr->stock_default;
        $data->company_default = $arr->company_default;
        $data->about = $arr->about;
        $data->active = $arr->active;
        $data->save();
         // Phân loại Sửa
         $arr->t = $type;

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
         $data = User::find($arr->id);
         if($data){
         $data->avatar = $pathname;
         $data->save();
         }
         //Lưu ảnh lại array
         $arr->avatar = $pathname;
       }
       //
       DB::commit(); 
       broadcast(new \App\Events\DataSend($arr));
       return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
       }else{
        return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission')]);
       }
     }else{
       return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
     }
  }else{
     return response()->json(['status'=>false,'message'=> trans('messages.username_is_already_taken')]);
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
        'url'  => $this->url,
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
            $data = User::find($arr->id);
          if(!$data){
            return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
          }
            // Lưu lịch sử
            $h = new HistoryAction();
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
          'type' => $type, // Add : 2 , Edit : 3 , Delete : 4 , Import : 5 , Export : 6, Timeline : 7 , Loadmore : 8, Load : 9
          'user_id' => Auth::id(),
          'menu_id' => $this->menu->id,
          'error' => $e->getMessage().' - Line '.$e->getLine(),
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
       $import = new UserImport;
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
       'error' => $e->getMessage().' - Line '.$e->getLine(),
       'url'  => $this->url,
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
       $myFile = Excel::raw(new UserExport($arr,$page), \Maatwebsite\Excel\Excel::XLSX);
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
       'error' => $e->getMessage().' - Line '.$e->getLine(),
       'url'  => $this->url,
       'check' => 0 ]);
     return response()->json(['status'=>false,'message'=> trans('messages.failed_export').' '.$e->getMessage().' - Line '.$e->getLine()]);
   }
 }

}
