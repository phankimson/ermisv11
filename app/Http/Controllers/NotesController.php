<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\Notes;
use App\Http\Model\Systems;
use App\Http\Model\HistoryAction;
use App\Http\Model\Menu;

class NotesController extends Controller
{
  public function __construct(Request $request)
 {
     $this->url = $request->segment(3);
     $this->key = "notes";
     $this->menu = Menu::where('code', '=', $this->key)->first();
 }
    public function show(){
       $sys = Systems::get_systems('MAX_NOTES');
       $notes = Notes::get_notes(0,$sys->value);
       return view('manage.notes',['notes' => $notes,'key' => $this->key]);
   }

   public function load(Request $request) {
     $type = 9;
        try{
          $req = json_decode($request->data);
          $data = Notes::find($req);
          return response()->json(['status'=>true,'data'=> $data ]);
        }catch(Exception $e){
          // Lưu lỗi
          $err = new Error();
          $err ->create([
            'type' => $type, // Add : 2 , Edit : 3 , Delete : 4 , Loadmore : 8, Load : 9
            'user_id' => Auth::id(),
            'menu_id' => $this->menu->id,
            'error' => $e->getMessage(),
            'url' => $this->url,
            'check' => 0 ]);
          return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
        }
      }

      public function loadMore(Request $request) {
        $type = 8;
           try{
             $arr = $request->data;
             $data = Notes::get_notes(($arr-1)*$sys->value,$arr*$sys->value);
             return response()->json(['status'=>true,'data'=> $data ]);
           }catch(Exception $e){
             // Lưu lỗi
             $err = new Error();
             $err ->create([
               'type' => $type, // Add : 2 , Edit : 3 , Delete : 4 , Loadmore : 8, Load : 9
               'user_id' => Auth::id(),
               'menu_id' => $this->menu->id,
               'error' => $e->getMessage(),
               'url' => $this->url,
               'check' => 0 ]);
             return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
           }
      }
      public function save(Request $request) {
        $type = 0;
           try{
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
                  // Lưu lịch sử
                  $h = new HistoryAction();
                  $h ->create([
                    'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
                    'user' => Auth::id(),
                    'menu' => $this->menu->id,
                    'url' => $this->url,
                    'dataz' => \json_encode($data)]);
                  //
                  // Lấy ID và và phân loại Thêm
                  $arr->id = $data->id;
                  $arr->type = $type;
                  broadcast(new \App\Events\DataSend($arr));
                  return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
                }else if($permission['e'] == true && $arr->id){
                  $type = 3;
                  $data = Notes::find($arr->id);
                  // Lưu lịch sử
                  $h = new HistoryAction();
                  $h ->create([
                    'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
                    'user' => Auth::id(),
                    'menu' => $this->menu->id,
                    'url' => $this->url,
                    'dataz' => \json_encode($data)]);
                  //
                  $data->title = $arr->title;
                  $data->message = $arr->message;
                  $data->active =  $arr->active=='on'? 1 : 0;
                  $data->save();
                  // Phân loại Sửa
                  $arr->type = $type;
                  broadcast(new \App\Events\DataSend($arr));
                  return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
                }else{
                  return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission')]);
                }
              }else{
                return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
              }
           }catch(Exception $e){
             // Lưu lỗi
             $err = new Error();
             $err ->create([
               'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
               'user_id' => Auth::id(),
               'menu_id' => $this->menu->id,
               'error' => $e->getMessage(),
               'url' => $this->url,
               'check' => 0 ]);
             return response()->json(['status'=>false,'message'=> trans('messages.update_fail').' '.$e->getMessage()]);
           }
      }

      public function delete(Request $request) {
        $type = 4;
           try{
             $permission = $request->session()->get('per');
             $arr = json_decode($request->data);
             if($arr){
               if($permission['d'] == true){
                 $data = Notes::find($arr->id);
                 // Lưu lịch sử
                 $h = new HistoryAction();
                 $h ->create([
                   'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
                   'user' => Auth::id(),
                   'menu' => $this->menu->id,
                   'url' => $this->url,
                   'dataz' => \json_encode($data)]);
                 //
                 $data->delete();
                 broadcast(new \App\Events\DataSend($arr));
                 return response()->json(['status'=>true,'message'=> trans('messages.delete_success')]);
               }else{
                 return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission_delete')]);
               }
            }else{
              return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
            }
           }catch(Exception $e){
             // Lưu lỗi
             $err = new Error();
             $err ->create([
               'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
               'user_id' => Auth::id(),
               'menu_id' => $this->menu->id,
               'error' => $e->getMessage(),
               'url' => $this->url,
               'check' => 0 ]);
             return response()->json(['status'=>false,'message'=> trans('messages.delete_fail').' '.$e->getMessage()]);
           }
      }
}
