<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Classes\SchemaDB;
use App\Classes\Convert;
use App\Http\Model\Company;
use App\Http\Model\Software;
use App\Http\Model\CompanySoftware;
use App\Http\Model\License;
use App\Http\Model\User;
use App\Http\Model\Systems;
use App\Http\Model\HistoryAction;
use App\Http\Model\Error;
use File;
use Hash;

class UserController extends Controller
{
  public function __construct(Request $request)
 {
     $this->url = $request->segment(3);
 }

  // Action Ajax User
 public function doLogout(Request $request){
     $user = Auth::user();
      // Lịch sử hoạt động
     $hs = HistoryAction::create(['type' =>  0 , 'url' => $request->segment(2) ,'user' =>$user->id , 'menu' => 0 , 'dataz' => '']);
     $request->session()->forget('status');
     Auth::logout();
     return redirect('/');
    }

 public function doLogin(Request $request){
     try{
       //validate the fields....
      $data = json_decode($request->data);
      $credentials = [ 'username' => $data->username , 'password' => $data->password , 'active' => 1];
      $capcha = data_get($data, 'g-recaptcha-response');
      if(Auth::attempt($credentials) && $capcha != ""){ // login attempt
        $user = Auth::user();
        // Kiểm tra role = admin không
        if($user->role == 0){
          // Lịch sử hoạt động
           $hs = HistoryAction::create(['type' =>  1 ,'url'=> $this->url , 'user' =>$user->id , 'menu' => 0 , 'dataz' => '']);

          return response()->json(['status'=>true, 'message'=> trans('messages.login_success')]);
        }else{
          $a = $request->session()->get('type');
          $cs = CompanySoftware::get_company_software($user->company_default,$a,1);
          if($cs){
            $date = date('Y-m-d');
            $date_end = date('Y-m-d',strtotime($cs->created_at->addDay($cs->free)));
            $lic = License::get_license($cs->license_id,$date,1);
            // Kiểm tra license của công ty
            if($lic || $date <= $date_end){
              // Authentication passed...

              // Lịch sử hoạt động
               $hs = HistoryAction::create(['type' =>  1 ,'url'=> $this->url, 'user' =>$user->id , 'menu' => 0 , 'dataz' => '']);

               return response()->json(['status'=>true, 'message'=> trans('messages.login_success')]);
            }else{
              Auth::logout();
              return response()->json(['status'=>false,'message'=> trans('messages.login_permission_2')]);
            }
          }else{
            Auth::logout();
            return response()->json(['status'=>false,'message'=> trans('messages.login_permission_1')]);
          }
        }
      }else{
         return response()->json(['status'=>false,'message'=> trans('messages.login_fail')]);
      }
     }catch(Exception $e){
       // Lưu lỗi
       $err = new Error();
       $err ->create([
         'type' => 9, // Add : 2 , Edit : 3 , Delete : 4
         'user_id' => Auth::id(),
         'menu_id' => 0,
         'error' => $e->getMessage(),
         'check' => 0 ]);
       return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
     }
   }

 public function doRegister(Request $request){
    try{
      //validate the fields...
      $data = json_decode($request->data);

      $password = Hash::make($data->password);

      // Tạo công ty
      $company = new Company;
      $company->code = $data->company_taxcode;
      $company->name = $data->company_name;
      $company->address = $data->company_address;
      $company->email = $data->company_email;
      $company->tax_code = $data->company_taxcode;
      $company->director = $data->company_director;
      $company->phone = $data->company_phone;
      $company->fax = $data->company_fax;
      $company->save();

      // Lấy dữ liệu phần mềm
      $software = Software::where('active',1)->get();
      $sys = Systems::get_systems('DATE_USE_FREE');
      $d = json_decode($request->data,true);
      foreach ($software as $value){
        $name = 'software_'.$value->id;
        if(isset($d[$name])){
          $company_software = new CompanySoftware;
          $company_software->company_id = $company->id;
          $company_software->software_id = $value->id;
          $company_software->free = $sys->value;
          $company_software->database = $value->url.'_'.$company->id;
          $company_software->active = 1;
          $company_software->save();

          // Tao database cho từng công ty
          //SchemaDB::createDB($company_software->database);
        }
      }

      // Tạo user khách hàng
      $user = new User;
      $user->username = $data->username;
      $user->password = $password; //hashed password.
      $user->fullname = $data->fullname;
      $user->firstname = $data->firstname;
      $user->lastname = $data->lastname;
      $user->identity_card = $data->identity_card;
      $user->birthday = Convert::dateDefaultformat($data->birthday,"Y-m-d");
      $user->phone = $data->phone;
      $user->email = $data->email;
      $user->address = $data->address;
      $user->city = $data->city;
      $user->jobs = $data->jobs;
      $user->country = $data->country;
      $user->about = $data->about;
      $user->avatar = 'addon/img/avatar.png';
      $user->role = 1 ;
      $user->active = 0;
      $user->active_code = Str::random(30);
      $user->company_default = $company->id;
      $user->save();

      //login as well.
      Auth::login($user,true);
      return response()->json(['status'=>true,'message'=> trans('messages.register_success')]);
      //redirect to your preferred url/route....
    }catch(Exception $e){
      // Lưu lỗi
      $err = new Error();
      $err ->create([
        'type' => 2, // Add : 2 , Edit : 3 , Delete : 4
        'user_id' => Auth::id(),
        'menu_id' => 0,
        'error' => $e->getMessage(),
        'check' => 0 ]);
      return response()->json(['status'=>false,'message'=> trans('messages.register_fail').' '.$e->getMessage()]);
    }
  }

 public function checkRegister(Request $request){
      try{
        //validate the fields...
        $data = json_decode($request->data);
        if($data->key == 1){
          $user = User::get_username($data->value,1);
          if($user){
            return response()->json(['status'=>false,'message'=> trans('messages.username_register')]);
          }else{
            return response()->json(['status'=>true]);
          }
        }elseif($data->key == 2){
          $company = Company::get_code($data->value);
          if($company){
            return response()->json(['status'=>false,'message'=> trans('messages.code_register')]);
          }else{
              return response()->json(['status'=>true]);
          }
        }else{
          $user = User::get_email($data->value,1);
          if($user){
            return response()->json(['status'=>false,'message'=> trans('messages.email_register')]);
          }else{
            return response()->json(['status'=>true]);
          }
        }
        }catch(Exception $e){
          // Lưu lỗi
          $err = new Error();
          $err ->create([
            'type' => 9, // Add : 2 , Edit : 3 , Delete : 4
            'user_id' => Auth::id(),
            'menu_id' => 0,
            'error' => $e->getMessage(),
            'check' => 0 ]);
          return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
        }
    }

 public function updateProfile(Request $request) {
      try{
        $data = json_decode($request->data);
        $user = User::find(Auth::id());
        $user->fullname = $data->fullname;
        $user->firstname = $data->firstname;
        $user->lastname = $data->lastname;
        $user->phone = $data->phone;
        $user->birthday = Convert::dateDefaultformat($data->birthday,"Y-m-d");
        $user->jobs = $data->jobs;
        $user->address = $data->address;
        $user->city = $data->city;
        $user->country = $data->country;
        $user->about = $data->about;
        $user->identity_card = $data->identity_card;
        $user->email = $data->email;
        $user->save();
        return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
      }catch(Exception $e){
        // Lưu lỗi
        $err = new Error();
        $err ->create([
          'type' => 3, // Add : 2 , Edit : 3 , Delete : 4
          'user_id' => Auth::id(),
          'menu_id' => 0,
          'error' => $e->getMessage(),
          'check' => 0 ]);
        return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
      }
    }

 public function changePassword(Request $request) {
   try{
    $data = json_decode($request->data);
    $user = User::find(Auth::id());
    $checkpassword = Hash::check($data->password, $user->password);
    $new_password = Hash::make($data->npassword);
     if($checkpassword && $user->password != $new_password && $data->npassword == $data->rpassword){
       $user->password = $new_password;
       $user->save();
       Auth::logout();
       return response()->json(['status'=>true,'message'=> trans('messages.change_password_success')]);
     }else{
       return response()->json(['status'=>false,'message'=> trans('messages.change_password_fail')]);
     }
   }catch(Exception $e){
     // Lưu lỗi
     $err = new Error();
     $err ->create([
       'type' => 3, // Add : 2 , Edit : 3 , Delete : 4
       'user_id' => Auth::id(),
       'menu_id' => 0,
       'error' => $e->getMessage(),
       'check' => 0 ]);
     return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
   }
  }

 public function updateAvatar (Request $request) {
      try{
        $request->validate([
           'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
      ]);

        if($request->hasFile('avatar')) {
        $avatar = $request->file('avatar');

        $user = User::find(Auth::id());
        $sys = Systems::get_systems('PATH_UPLOAD_AVATAR');

        $filename = time().'.'.$avatar->getClientOriginalExtension();

        $path = public_path().'/'.$sys->value . Auth::id();

        if(!File::isDirectory($path)){
        File::makeDirectory($path, 0777, true, true);
        }

        $upload_success = $avatar->move($path, $filename);

        if (!$upload_success) {
          return response()->json(['status'=>false,'message'=> trans('messages.failed_upload')]);
        }

        // delete old avatar
        if(File::exists(public_path($user->avatar)) && $user->avatar != 'addon/img/avatar.png'){
           File::delete(public_path($user->avatar));
        };

        $user->avatar = $sys->value . Auth::id().'/'.$filename;
        $user->save();

        broadcast(new \App\Events\UserStatus($user));

       return response()->json(['status'=>true,'message'=> trans('messages.success_upload') ,'data' => $user]);
        }
      }catch(Exception $e){
        // Lưu lỗi
        $err = new Error();
        $err ->create([
          'type' => 3, // Add : 2 , Edit : 3 , Delete : 4
          'user_id' => Auth::id(),
          'menu_id' => 0,
          'error' => $e->getMessage(),
          'check' => 0 ]);
        return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
      }
 }

 public function loadHistoryAction(Request $request) {
   try{
     $arr = json_decode($request->data);
     $user = Auth::user();
     $data = HistoryAction::get_skip($user->id,($arr->current-1)*$arr->length,$arr->length);
     if($data->count()>0){
       return response()->json(['status'=>true,'data'=> $data,'length'=>$arr->length ]);
     }else{
       return response()->json(['status'=>false, 'message'=> trans('messages.no_data_found')]);
     }
   }catch(Exception $e){
     // Lưu lỗi
     $err = new Error();
     $err ->create([
       'type' => 9, // Add : 2 , Edit : 3 , Delete : 4
       'user_id' => Auth::id(),
       'menu_id' => 0,
       'error' => $e->getMessage(),
       'check' => 0 ]);
     return response()->json(['status'=>false,'message'=> trans('messages.error') . $e->getMessage()]);
   }
 }

}
