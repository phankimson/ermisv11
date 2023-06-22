<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Model\Menu;

class TestController extends Controller
{
  public function get(Request $request){
      $type = 1;
      $data = Menu::get_menu_raw_type($type);
      return response()->json(['status'=>true,'data'=> $data ]);
 }

  public function test(){
    $data = Menu::get_menu_raw_type(2);
     return view('global.test',['data' => $data]);
  }
  public function test1(){
     return view('global.test1');
  }
  public function test2(){
     return view('global.test1');
  }

}
