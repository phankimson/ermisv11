<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\Country;
use Illuminate\Support\Facades\Auth;

class AccHomeController extends Controller
{
  public function show(){
     return view('acc.index');
  }

  public function profile(){
      $ha =  AccHistoryAction::get_menu(Auth::id(),10,[2,3,4]);
      //return dd($ha);
      $country = Country::all();
      return view('global.profile',['history_action' => $ha ,'country' => $country]);
  }

}
