<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\Country;
use App\Classes\SchemaDB;
use Illuminate\Support\Facades\Auth;

class AccHomeController extends Controller
{
  public function show(Request $request){
    $mysql2 = $request->session()->get('mysql2');
    config(['database.connections.mysql2' => $mysql2]);
     return view('acc.index');
  }

  public function profile(Request $request){
      $mysql2 = $request->session()->get('mysql2');
      config(['database.connections.mysql2' => $mysql2]);
      $ha =  AccHistoryAction::get_menu(Auth::id(),10,[2,3,4]);
      //return dd($ha);
      $country = Country::all();
      return view('global.profile',['history_action' => $ha ,'country' => $country]);
  }

}
