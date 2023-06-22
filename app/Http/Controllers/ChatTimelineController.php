<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\Timeline;
use App\Http\Model\Chat;
use App\Http\Model\Systems;
use App\Http\Model\Error;

class ChatTimelineController extends Controller
{
  public function __construct(Request $request)
 {
     $this->url = $request->segment(3);
 }

public function timeline(Request $request){
  $type = 7;
    try{
      $data = json_decode($request->data);
      $user = Auth::user();
      $timeline = new Timeline;
      $timeline->user_id = $user->id;
      $timeline->type = $data->type;
      $timeline->message = $data->message;
      $timeline->save();
      $data->user = $user->username;
      $data->created_at = $timeline->created_at;
       broadcast(new \App\Events\ChatTimeline($data));
       return ['status' => true];
    }catch(Exception $e){
      // Lưu lỗi
      $err = new Error();
      $err ->create([
        'type' => $type, // Add : 2 , Edit : 3 , Delete : 4 , Import : 5 , Export : 6, Timeline : 7 , Loadmore : 8, Load : 9
        'user_id' => Auth::id(),
        'menu_id' => 0,
        'error' => $e->getMessage(),
        'url'  => $this->url,
        'check' => 0 ]);
      return response()->json(['status'=>false,'message'=> trans('messages.error') . $e->getMessage()]);
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
       // Lưu lỗi
       $err = new Error();
       $err ->create([
         'type' => $type, // Add : 2 , Edit : 3 , Delete : 4 , Import : 5 , Export : 6, Timeline : 7 , Loadmore : 8, Load : 9
         'user_id' => Auth::id(),
         'menu_id' => 0,
         'error' => $e->getMessage(),
         'url'  => $this->url,
         'check' => 0 ]);
       return response()->json(['status'=>false,'message'=> trans('messages.error'). $e->getMessage()]);
     }
 }

public function doChat(Request $request){
  $type = 7;
  try{
    $data = json_decode($request->data);
    $user = Auth::user();
    $chat = new Chat;
    $chat->user_send = $user->id;
    $chat->user_receipt = $data->user_receipt;
    $chat->message = $data->message;
    $chat->save();
    $data->user_send = $user->username;
     broadcast(new \App\Events\UserChat($data,$data->user_receipt));
     return ['status' => true];
  }catch(Exception $e){
    // Lưu lỗi
    $err = new Error();
    $err ->create([
      'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
      'user_id' => Auth::id(),
      'menu_id' => 0,
      'error' => $e->getMessage(),
      'url'  => $this->url,
      'check' => 0 ]);
    return response()->json(['status'=>false,'message'=> trans('messages.error') . $e->getMessage() ]);
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
    // Lưu lỗi
    $err = new Error();
    $err ->create([
      'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
      'user_id' => Auth::id(),
      'menu_id' => 0,
      'error' => $e->getMessage(),
      'url'  => $this->url,
      'check' => 0 ]);
    return response()->json(['status'=>false,'message'=> trans('messages.error') . $e->getMessage()]);
  }
}

}
