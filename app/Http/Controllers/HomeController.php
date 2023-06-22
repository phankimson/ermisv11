<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Model\Software;
use App\Http\Model\HistoryAction;
use App\Http\Model\Country;
use App\Http\Model\Company;
use App\Http\Model\CompanySoftWare;
use App\Http\Model\User;
use App\Http\Model\Error;
use App\Classes\NumberConvert;
use Illuminate\Support\Facades\Auth;
use Hash;

class HomeController extends Controller
{

  public function login(Request $request){
    if(Auth::check()){
      $manage = $request->session()->get('manage');
      return redirect($manage.'/index');
    }else{
      return view('global.login');
    }
 }

  public function register(){
       $software = Software::active()->get();
       return view('global.register',['software' => $software]);
    }

  public function index(){
      $software = Software::active()->get();
      return view('global.home',['software' => $software]);
  }

  public function show(){
     $company = Company::active()->count();
     $database = CompanySoftWare::active()->count();
     $user = User::active()->count();
     $error = Error::get_check("0")->count();
     return view('global.index',['company' => $company,'database'=>$database,'user'=>$user,'error'=>$error]);
  }

  public function profile(){
      $ha = HistoryAction::get_menu(Auth::id(),10,[2,3,4])->get();
      $country = Country::all();
      return view('global.profile',['history_action' => $ha ,'country' => $country]);
  }

  public function block(){
      if(Auth::check()){
        return view('global.block');
      }else{
        return view('global.login');
      }
  }

  public function welcome($name){
     return view('global.welcome',['name' => $name]);
  }

}
