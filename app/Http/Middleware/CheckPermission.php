<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use View;
use App\Http\Model\Permission;
use App\Http\Model\Software;
use App\Http\Model\CompanySoftware;
use App\Http\Model\Company;
use App\Classes\bitmask;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
  public function __construct(Application $app, Redirector $redirector, Request $request) {
     $this->app = $app;
     $this->redirector = $redirector;
     $this->request = $request;
 }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $locale = $this->app->getLocale();
      $software = Software::all();
      $manage = '';
      $link = $this->request->url();
      $type = 0;
      $skip = false;
      $skip_load = ['login','logout','block'];
      $st = 0;
      foreach ($software as $value){
        $a = $value->url;
        if(strpos($link,$value->url) > 0){
          $manage = $value->url;
          $st = strlen($link) - strpos($link,$value->url) - strlen($value->url);
          $type = $value->id;
          break;
      }
    };
      foreach ($skip_load as $s){
          if(strpos($link,$s) > 0){
            $skip = true;
            break;
        }
      };
      $user = Auth::user();
      View::share('manage',$manage);
      $request->session()->put('manage', $manage);
      $request->session()->put('type', $type);
      if($link != null && $skip == false && $manage != '' && $this->request->method() == "GET" ){
          $sl = CompanySoftware::get_company_software_with_license(isset($user->company_default)?$user->company_default:null,$type,1);
          if($sl){
              // Thông tin database company
              $check_database = false;
            if($request->session()->has('mysql2')){
              $params = $request->session()->get('mysql2');
              if($sl->database == $params['database']){
                $check_database = true;
              }
            }

            if($check_database == false){
              $params = array(
                    'driver'    => env('DB_CONNECTION', 'mysql'),
                    'host'      => env('DB_HOST', '127.0.0.1'),
                    'database'  => $sl->database,
                    'username'  => $sl->username,
                    'password'  => $sl->password,
                    'charset'   => 'utf8',
                    'collation' => 'utf8_unicode_ci',
                    'prefix'    => '',
                    'strict'    => false,
                );
                $request->session()->put('mysql2', $params);
                View::share('db',$params['database']);
            }else{
              $params = $request->session()->get('mysql2');
              View::share('db',$params['database']);
            }
              // Lấy tên công ty
            $com = Company::find($sl->company_id);
            $request->session()->put('com', $com);
            $date = date('Y-m-d');
            $date_end = date('Y-m-d',strtotime($sl->created_at->addDay($sl->free)));
            if($date <= $date_end || $date<=$sl->date_end || $user->role == 0){
                 if($user){
                    $permission = 0;
                  if($user->role == 0){
                    $permission = 63;
                    }else if($user->role == 1 && $manage != 'manage'){
                    $permission = 63;
                  }else{
                    $per = Permission::get_user_permission($link,$user->id);
                    if($per){
                    $permission = $per->permission;
                    }
                  }
                  $bitmask = new bitmask();
                  $arr = $bitmask->getPermissions($permission);
                  if($arr['v'] === true){
                      View::share('per',$arr);
                      $request->session()->put('per', $arr);
                   }else{
                      $request->session()->forget('status');
                      return redirect($locale.'/'.$manage.'/'.$skip_load[2]);
                   }
                }else{
                  if($st!=0){
                    $request->session()->flash('status', trans('messages.you_are_not_permission'));
                  }
                    return redirect($locale.'/'.$manage.'/'.$skip_load[0]);
                }
            }else{
              if($st!=0 && Auth::user()){
                $request->session()->flash('status', trans('messages.software_has_expired'));
              }
              Auth::logout();
              return redirect($locale.'/'.$manage.'/'.$skip_load[0]);
            }
          }else{
            if($st!=0 && Auth::user()){
              $request->session()->flash('status', trans('messages.software_not_register'));
            }
            Auth::logout();
            return redirect($locale.'/'.$manage.'/'.$skip_load[0]);
          }
      }
      return $next($request);
    }
}
