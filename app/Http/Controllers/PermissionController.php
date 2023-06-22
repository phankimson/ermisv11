<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\Permission;
use App\Classes\bitmask;
use App\Http\Model\Menu;
use App\Http\Model\GroupUsers;
use App\Http\Model\GroupUsersPermission;
use App\Http\Model\Error;
use App\Http\Model\User;
use App\Http\Model\HistoryAction;

class PermissionController extends Controller
{
    public function __construct(Request $request)
   {
       $this->url = $request->segment(3);
       $this->key = "permission";
       $this->menu = Menu::where('code', '=', $this->key)->first();
   }

   public function show(Request $request){
      $com = $request->session()->get('com');
      $group_users = GroupUsers::ActiveCompany($com->id)->get();
      return view('global.permission',['key' => $this->key,'group_users' =>$group_users]);
   }

   public function load(Request $request) {
     $type = 9;
        try{
          $req = json_decode($request->data);
          $data = Permission::get_user_permission_all($req);
          if($data){
            $arr = collect();
            foreach ($data as $d){
              $bitmask = new bitmask();
              $p = $bitmask->getPermissions($d->permission);
              $p['id']= $d->id;
              $p['menu'] = $d->menu_id;
              $arr->push($p);
            }
            return response()->json(['status'=>true,'data'=> $arr ]);
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
          return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
        }
      }

      public function group(Request $request) {
        $type = 9;
           try{
             $req = json_decode($request->data);
             $data = GroupUsersPermission::get_permission_all($req);
             if($data){
               $arr = collect();
               foreach ($data as $d){
                 $bitmask = new bitmask();
                 $p = $bitmask->getPermissions($d->permission);
                 $p['id']= $d->id;
                 $p['menu'] = $d->menu_id;
                 $arr->push($p);
               }
               return response()->json(['status'=>true,'data'=> $arr ]);
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
             return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
           }
         }

      public function save(Request $request) {
        $type = 0;
           try{
             $permission = $request->session()->get('per');
             $data = json_decode($request->data);
              if($data){
                if(($permission['a'] == true  || $permission['e'] == true)){
                  foreach ($data as $d){
                    if($d->group_user == 1){
                      if(!$d->id){
                        $result = new GroupUsersPermission();
                        $type = 2;
                        $result->group_user_id = $d->user;
                      }else{
                        $result = GroupUsersPermission::find($d->id);
                        $type = 3;
                        $result->group_user_id = $d->user;
                      }
                    }else{
                      if(!$d->id){
                        $result = new Permission();
                        $type = 2;
                        $result->user_id = $d->user;
                      }else{
                        $result = Permission::find($d->id);
                        $type = 3;
                        $result->user_id = $d->user;
                      }
                    }

                    $result->menu_id = $d->menu;
                    $result->permission = $d->permission;
                    $result->save();
                    // Lưu lịch sử
                    $h = new HistoryAction();
                    $h ->create([
                      'type' => $type, // Add : 1 , Edit : 2 , Delete : 3
                      'user' => Auth::id(),
                      'menu' => $this->menu->id,
                      'url' => $this->url,
                      'dataz' => \json_encode($result)]);
                    //
                  }
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
}
