<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\Timeline;
use App\Http\Model\Chat;
use App\Http\Model\Systems;
use Exception;
use Illuminate\Support\Facades\DB;

class ChatTimelineController extends Controller
{
  protected $url;
  protected $menu = null;
  public function __construct(Request $request)
 {
     $this->url = $request->segment(3);
 }

public function timeline(Request $request){
  $type = 7;
    try{
      DB::beginTransaction();
      $data = json_decode($request->data);
      $user = Auth::user();
      $timeline = new Timeline;
      $timeline->user_id = $user->id;
      $timeline->type = $data->type;
      $timeline->message = $data->message;
      $timeline->save();
      $data->user = $user->username;
      $data->created_at = $timeline->created_at;
      DB::commit();
       broadcast(new \App\Events\ChatTimeline($data));
       return ['status' => true];
    }catch(Exception $e){
      DB::rollBack();
      return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
    }
  }

public function viewMore(Request $request){
  $type = 8;
     try{
      $arr = $request->data;
      $sys = Systems::get_systems('MAX_TIMELINE');
      $data = Timeline::get_timeline(($arr-1)*$sys->value,$arr*$sys->value);
      if($data->count()>0){
        return response()->json(['status'=>true,'data'=> $data ]);
      }else{
        return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
      }
     }catch(Exception $e){
       return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
     }
 }

public function doChat(Request $request){
  $type = 7;
  try{
    DB::beginTransaction();
    $data = json_decode($request->data);
    $user = Auth::user();
    $chat = new Chat;
    $chat->user_send = $user->id;
    $chat->user_receipt = $data->user_receipt;
    $chat->message = $data->message;
    $chat->save();
    $data->user_send = $user->username;
    DB::commit();
     broadcast(new \App\Events\UserChat($data,$data->user_receipt));
     return ['status' => true];
  }catch(Exception $e){
    DB::rollBack();
    return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
  }
}

public function loadChatUser(Request $request) {
  $type = 9;
  try{
    $arr = json_decode($request->data);
    $user = Auth::user();
    $sys = Systems::get_systems('MAX_LOAD_CHAT');
    $data = Chat::get_chat($user->id,$arr->user_receipt,$sys->value*($arr->page-1),$sys->value*$arr->page);
    if($data->count()>0){
      return response()->json(['status'=>true,'data'=> $data ]);
    }else{
      return response()->json(['status'=>false, 'message'=> trans('messages.no_data_found')]);
    }
  }catch(Exception $e){
    return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
  }
}

}
