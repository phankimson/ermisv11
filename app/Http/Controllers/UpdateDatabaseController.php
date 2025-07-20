<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\Menu;
use App\Http\Model\Error;
use App\Http\Model\CompanySoftware;
use App\Classes\SchemaDB;
use Illuminate\Support\Facades\DB;
use Exception;

class UpdateDatabaseController extends Controller
{
  protected $url;
  protected $key;
  protected $menu;
    public function __construct(Request $request)
   {
       $this->url = $request->segment(3);
       $this->key = "update-database";
       $this->menu = Menu::where('code', '=', $this->key)->first();
   }

   public function show(){
      return view('manage.update_database',['key' => $this->key]);
   }

   public function GetTableDatabase(Request $request){
     $type = 9;
     try{
       $req = json_decode($request->data);
      if($req->database !="" && $req->host !=""){ 
          $params = array(
                'driver'    => env('DB_CONNECTION', 'mysql4'),
                'host'      => $req->host,
                'database'  => $req->database,
                'username'  => $req->username,
                'password'  => $req->password==""?null:$req->password,
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
                'strict'    => false,
            );
            config(['database.connections.mysql4' => $params]);
            $con = 'mysql4';
            DB::connection($con);
            $tables = DB::select('SHOW TABLES');
            return response()->json(['status'=>true ,'message'=> trans('messages.connect_success'),'tables'=>$tables]);       
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
        $results =  DB::connection($con)->select($sql);
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
