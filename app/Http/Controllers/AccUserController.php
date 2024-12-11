<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\Error;
use Exception;

class AccUserController extends Controller
{
  protected $url;
  public function __construct(Request $request)
 {
     $this->url =  $request->segment(3);
 }

 public function loadHistoryAction(Request $request) {
   try{
     $arr = json_decode($request->data);
     $user = Auth::user();
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
