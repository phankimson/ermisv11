<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\AccHistoryAction;
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
     return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
   }
 }

}
