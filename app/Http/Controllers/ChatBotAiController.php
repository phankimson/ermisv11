<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\Error;
use Exception;
use Illuminate\Support\Facades\DB;
use OpenAI\Laravel\Facades\OpenAI;

class ChatBotAiController extends Controller
{
  protected $url;
  public function __construct(Request $request)
 {
     $this->url = $request->segment(3);
 }

public function doChatBotAI(Request $request){
  $type = 0;
  try{
    $data = json_decode($request->data);
    $result = OpenAI::chat()->create([
    'model' => 'gpt-4o-mini',
    'messages' => [
        ['role' => 'user', 'content' => $data->message],
        ],
    ]);
     return response()->json(['status'=>true,'content'=> $result->choices[0]->message->content ]);
  }catch(Exception $e){
    DB::rollBack();
    // Lưu lỗi
    $err = new Error();
    $err ->create([
      'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
      'user_id' => Auth::id(),
      'menu_id' => 0,
      'error' => $e->getMessage().' - Line '.$e->getLine(),
      'url'  => $this->url,
      'check' => 0 ]);
    return response()->json(['status'=>false,'message'=> trans('messages.error') . $e->getMessage().' - Line '.$e->getLine() ]);
  }
}

}
