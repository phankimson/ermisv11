<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\Menu;
use App\Http\Model\AccPeriod;
use App\Http\Model\AccGeneral;
use App\Http\Model\CompanySoftware;
use App\Http\Model\Company;
use App\Http\Model\Error;
use Illuminate\Support\Str;
use Excel;
use Carbon\Carbon;

class AccPeriodController extends Controller
{
  public function __construct(Request $request)
 {
     $this->url =  $request->segment(3);
     $this->key = "period";
     $this->menu = Menu::where('code', '=', $this->key)->first();
 }

  public function show(Request $request){
    $mysql2 = $request->session()->get('mysql2');
    config(['database.connections.mysql2' => $mysql2]);
    $data = AccPeriod::get_raw();
    return view('acc.period',['data' => $data, 'key' => $this->key ]);
  }

  public function ChangeDatabase(Request $request){
    $type = 9;
    try{
      $req = json_decode($request->data);
      $db = CompanySoftware::find($req->database);
      $com = Company::find($db->company_id);
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
      $request->session()->put('mysql2', $params);
      config(['database.connections.mysql2' => $params]);
      $data = AccPeriod::get_raw();
      return response()->json(['status'=>true,'data'=> $data,'com_name'=> $com->name ]);
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

  public function save(Request $request){
    $mysql2 = $request->session()->get('mysql2');
    config(['database.connections.mysql2' => $mysql2]);
    $type = 0;
    try{
  $permission = $request->session()->get('per');
  $arr = json_decode($request->data);
  $validator = Validator::make(collect($arr)->toArray(),[
            'date' => ['required']
        ]);
     if($validator->passes()){
     if($permission['a'] == true){
       $formatDate = Carbon::createFromFormat('!m/Y',$arr->date);
       $formatMonth = $formatDate->format('Y-m');
       $check = AccPeriod::get_date($formatMonth,1);
       $startDate = $formatDate->startOfMonth()->format('Y-m-d');
       $endDate = $formatDate->endOfMonth()->format('Y-m-d');
       $general = AccGeneral::get_range_date(null,null,$startDate,$endDate);
       if($check && $check->id != $arr->id){
         return response()->json(['status'=>false,'message'=> trans('messages.duplicate_date')]);
       }else if($general->count()>0){
         $voucher = '';
         foreach($general as $g){
           $voucher .= $g->voucher.' ';
         };
         return response()->json(['status'=>false,'message'=> trans('messages.voucher_not_active').' @ '.$voucher]);
       }else{
         $data = AccPeriod::get_date($formatMonth,0);
         if($data){
           $type = 2;
           $data->active = 1;
           $data->save();
         }else{
           $type = 2;
           $data = new AccPeriod();
           $data->name = "Khóa kỳ ".$arr->date;
           $data->name_en = "Lock period ".$arr->date;
           $data->date = $formatMonth;
           $data->active = 1;
           $data->save();
         }

         // Lưu lịch sử
         $h = new AccHistoryAction();
         $h ->create([
           'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
           'user' => Auth::id(),
           'menu' => $this->menu->id,
           'url'  => $this->url,
           'dataz' => \json_encode($data)]);

         // Lấy ID và và phân loại Thêm
         $merge = collect((array)$arr);
         $merge = json_decode($merge->merge($data->toArray())->toJson());
         $merge->t = $type;
         broadcast(new \App\Events\DataSend($merge));
         return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
       }

     }else{
        return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission')]);
       }
     }else{
       return response()->json(['status'=>false,'error'=>$validator->getMessageBag()->toArray() ,'message'=>trans('messages.error')]);
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

 public function delete(Request $request) {
   $mysql2 = $request->session()->get('mysql2');
   config(['database.connections.mysql2' => $mysql2]);
   $type = 4;
      try{
        $permission = $request->session()->get('per');
        $arr = json_decode($request->data);
        if($arr){
          if($permission['d'] == true){
            $data = AccPeriod::find($arr->id);
            // Lưu lịch sử
            $h = new AccHistoryAction();
            $h ->create([
            'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
            'user' => Auth::id(),
            'menu' => $this->menu->id,
            'url'  => $this->url,
            'dataz' => \json_encode($data)]);
            //
            $data->active = 0 ;
            $data->save();
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
          'url'  => $this->url,
          'check' => 0 ]);
        return response()->json(['status'=>false,'message'=> trans('messages.delete_fail').' '.$e->getMessage()]);
      }
 }

}
