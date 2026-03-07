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
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\HistoryTraits;

class PermissionController extends Controller
{
  use HistoryTraits;
    protected $url;
    protected $key;
    protected $menu;
    public function __construct(Request $request)
   {
       $this->url = $request->segment(3);
       $this->key = "permission";
       $this->menu = Menu::where('code', '=', $this->key)->first();
   }

   public function show(Request $request){
      $com = $request->session()->get('com');
      $group_users = GroupUsers::ActiveCompany($com->id)->get();
      return view('global.'.$this->key,['key' => $this->key,'group_users' =>$group_users]);
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
          return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
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
             return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
           }
         }

      public function save(Request $request) {
        $type = 0;
           try{
            DB::beginTransaction();
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
                        if(!$result){
                          DB::rollBack();
                          return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
                        }
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
                        if(!$result){
                          DB::rollBack();
                          return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
                        }
                        $type = 3;
                        $result->user_id = $d->user;
                      }
                    }

                    $result->menu_id = $d->menu;
                    $result->permission = $d->permission;
                    $result->save();
                    // Luu lich su them moi va sua doi
                     $this->create_history($type,Auth::id(),$this->menu->id,$this->url,$result);
                    //
                  }
                  DB::commit();  
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
}
