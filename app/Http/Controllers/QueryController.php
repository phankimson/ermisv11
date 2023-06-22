<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\Menu;
use App\Http\Model\Error;
use App\Http\Model\CompanySoftware;
use App\Classes\SchemaDB;
use DB;
use Config;

class QueryController extends Controller
{
    public function __construct(Request $request)
   {
       $this->url = $request->segment(3);
       $this->key = "query";
       $this->menu = Menu::where('code', '=', $this->key)->first();
   }

   public function show(){
      return view('manage.query',['key' => $this->key]);
   }

   public function ChangeDatabase(Request $request){
     $type = 9;
     try{
       $req = json_decode($request->data);
       $db = CompanySoftware::find($req->database);
       $checkDB = SchemaDB::checkDB($db->database);
       if($checkDB == 1){
         $params = array(
               'driver'    => env('DB_CONNECTION', 'mysql'),
               'host'      => env('DB_HOST', '127.0.0.1'),
               'database'  => $db->database,
               'username'  => $db->username,
               'password'  => $db->password,
               'charset'   => 'utf8',
               'collation' => 'utf8_unicode_ci',
               'prefix'    => '',
               'strict'    => false,
           );
           $request->session()->put('mysql3', $params);
           return response()->json(['status'=>true ,'message'=> trans('messages.connect_success')]);
       }else{
           return response()->json(['status'=>false ,'message'=> trans('messages.connect_fail')]);
       }

     }catch(Exception $e){
       // Lưu lỗi
       $err = new Error();
       $err ->create([
         'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
         'user_id' => Auth::id(),
         'menu_id' => $this->menu->id,
         'error' => $e->getMessage(),
         'url'  => $this->url,
         'check' => 0 ]);
       return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
     }
  }

   public function query(Request $request){
       $type = 9;
       try {
       $t = json_decode($request->data);
       $sql = $t->query;
       $check = $request->session()->has('mysql3');
       if($check == true){
        $params = $request->session()->get('mysql3');
        config(['database.connections.mysql3' => $params]);
        $con = 'mysql3';
       }else{
        $con = 'mysql';
      };
       if(strpos($sql,'select') !== false){
        $results =  DB::connection($con)->select(DB::raw($sql));
       }else{
        $results = DB::connection($con)->statement($sql);
        }
       return response()->json([
               'status'  => true,
               'data'    => $results,
               'message' => trans('messages.update_success'),
           ]);
        } catch (\Exception $e) {
          // Lưu lỗi
          $err = new Error();
          $err ->create([
            'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
            'user_id' => Auth::id(),
            'menu_id' => $this->menu->id,
            'error' => $e->getMessage(),
            'url' => $this->url,
            'check' => 0 ]);
           //
             return response()->json([
               'status'  => false,
               'message' => $e->getMessage(),
           ]);
        }
   }

}
