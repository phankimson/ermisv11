<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use OpenAI\Laravel\Facades\OpenAI;

class ChatBotAiController extends Controller
{
  protected $url;
  protected $menu = null;
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
    return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
  }
}

}
