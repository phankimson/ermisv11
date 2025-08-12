<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\Systems;
use App\Http\Model\Error;
use App\Http\Model\Menu;
use Exception;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
  protected $url;
  protected $key;
  protected $menu;
  
    public function __construct(Request $request)
   {
       $this->url = $request->segment(3);
       $this->menu = Menu::where('code', '=', $this->key)->first();
       $this->key = "setting";
   }

   public function show(){
      $sys = Systems::all();
      return view('global.'.$this->key,['sys' => $sys, 'key' => $this->key]);
   }

  public function changeSetting(Request $request){
    $type = 3;
      try{
        DB::beginTransaction();
        $data = json_decode($request->data);
        foreach($data as $k => $v){
          if($k != 'action' && $k != 'key' && $k != 'com' ) {
            $ex = explode("-",$k);
            if(!empty($ex[0]) && $ex[0] === 'value1'){
            $return = Systems::get_systems($ex[1]);
            $return->value1 = $v;
            $return->save();
          }else if(!empty($ex[0]) && $ex[0] === 'value2'){
            $return = Systems::get_systems($ex[1]);
            $return->value2 = $v;
            $return->save();
          }else if(!empty($ex[0]) && $ex[0] === 'value3'){
            $return = Systems::get_systems($ex[1]);
            $return->value3 = $v;
            $return->save();
          }else if(!empty($ex[0]) && $ex[0] === 'value4'){
            $return = Systems::get_systems($ex[1]);
            $return->value4 = $v;
            $return->save();
          }else if(!empty($ex[0]) && $ex[0] === 'value5'){
            $return = Systems::get_systems($ex[1]);
            $return->value5 = $v;
            $return->save();
          }else{
            $return = Systems::get_systems($k);
            $return->value = $v;
            $return->save();
            }
          }
         }
         DB::commit();  
         broadcast(new \App\Events\DataSend($data));
        return response()->json(['status'=>true,'message'=> trans('messages.update_success') ]);
      }catch(Exception $e){
        DB::rollBack();
        // Lưu lỗi
        $err = new Error();
        $err ->create([
          'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
          'user_id' => Auth::id(),
          'menu_id' => $this->menu->id,
          'error' => $e->getMessage().' - Line '.$e->getLine(),
          'url' => $this->url,
          'check' => 0 ]);
        return response()->json(['status'=>false,'message'=> trans('messages.error') . $e->getMessage().' - Line '.$e->getLine()]);
      }
    }


}
