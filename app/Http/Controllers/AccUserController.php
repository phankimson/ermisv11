<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\SchemaDB;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\Error;

class AccUserController extends Controller
{
  public function __construct(Request $request)
 {
     $this->url =  $request->segment(3);
 }

 public function loadHistoryAction(Request $request) {
   try{
     $arr = json_decode($request->data);
     $user = Auth::user();
     $mysql2 = $request->session()->get('mysql2');
     config(['database.connections.mysql2' => $mysql2]);
     $data = AccHistoryAction::get_skip($user->id,($arr->current-1)*$arr->length,$arr->length);

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
       'url'  => $this->url,
       'check' => 0 ]);
     return response()->json(['status'=>false,'message'=> trans('messages.error') . $e->getMessage()]);
   }
 }

}
