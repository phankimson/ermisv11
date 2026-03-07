<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\Notes;
use App\Http\Model\Systems;
use App\Http\Model\Menu;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\HistoryTraits;

class NotesController extends Controller
{
  use HistoryTraits;
  protected $url;
  protected $key;
  protected $menu;

  public function __construct(Request $request)
 {
     $this->url = $request->segment(3);
     $this->key = "notes";
     $this->menu = Menu::where('code', '=', $this->key)->first();
 }
    public function show(){
       $sys = Systems::get_systems('MAX_NOTES');
       $notes = Notes::get_notes(0,$sys->value);
       return view('manage.'.$this->key,['notes' => $notes,'key' => $this->key]);
   }

   public function load(Request $request) {
     $type = 9;
        try{
          $req = json_decode($request->data);
          $data = Notes::find($req);
          if(!$data){
          return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
        }
          return response()->json(['status'=>true,'data'=> $data ]);
        }catch(Exception $e){
          return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
        }
      }

      public function loadMore(Request $request) {
        $type = 8;
           try{
             $arr = $request->data;
             $sys = Systems::get_systems('MAX_NOTES');
             $data = Notes::get_notes(($arr-1)*$sys->value,$arr*$sys->value);
             return response()->json(['status'=>true,'data'=> $data ]);
           }catch(Exception $e){
             return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
           }
      }
      public function save(Request $request) {
        $type = 0;
           try{
            DB::beginTransaction();
             $permission = $request->session()->get('per');
             $arr = json_decode($request->data);
              if($arr){
                if($permission['a'] == true && !$arr->id ){
                  $type = 2;
                  $data = new Notes();
                  $data->title = $arr->title;
                  $data->message = $arr->message;
                  $data->active = $arr->active=='on'? 1 : 0;
                  $data->user_id = Auth::id();
                  $data->save();
                  // Luu lich su them moi
                   $this->create_history($type,Auth::id(),$this->menu->id,$this->url,$data);
                  //
                  // Lay ID vừa luu de truyền lên socket
                  $arr->id = $data->id;
                  $arr->type = $type;
                  DB::commit();  
                  broadcast(new \App\Events\DataSend($arr));
                  return response()->json(['status'=>true,'message'=> trans('messages.add_success')]);
                }else if($permission['e'] == true && $arr->id){
                  $type = 3;
                  $data = Notes::find($arr->id);
                  if(!$data){
                    return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
                  }
                  // Luu lich su sua doi
                   $this->create_history($type,Auth::id(),$this->menu->id,$this->url,$data);
                  //
                  $data->title = $arr->title;
                  $data->message = $arr->message;
                  $data->active =  $arr->active=='on'? 1 : 0;
                  $data->save();
                  // Phan loai sua doi de truyen len socket
                  $arr->type = $type;
                  DB::commit();  
                  broadcast(new \App\Events\DataSend($arr));
                  return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
                }else{
                  return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission')]);
                }
              }else{
                return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
              }
           }catch(Exception $e){
            DB::rollBack();
            return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__, 'messages.update_fail');
           }
      }

      public function delete(Request $request) {
        $type = 4;
           try{
            DB::beginTransaction();
             $permission = $request->session()->get('per');
             $arr = json_decode($request->data);
             if($arr){
               if($permission['d'] == true){
                 $data = Notes::find($arr->id);
                 if(!$data){
                    return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
                  }
                 // Luu lich su xoa
                 $this->create_history($type,Auth::id(),$this->menu->id,$this->url,$data);
                 //
                 $data->delete();
                 DB::commit();
                 broadcast(new \App\Events\DataSend($arr));
                 return response()->json(['status'=>true,'message'=> trans('messages.delete_success')]);
               }else{
                 return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission_delete')]);
               }
            }else{
              return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
            }
           }catch(Exception $e){
            DB::rollBack();
            return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__, 'messages.delete_fail');
           }
      }
}
