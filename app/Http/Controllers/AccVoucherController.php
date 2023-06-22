<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\Menu;
use App\Http\Model\AccGeneral;
use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccAccountedAuto;
use App\Http\Model\AccAccountedFast;
use App\Http\Model\AccCurrency;
use App\Http\Resources\ObjectDropDownListResource;
use App\Http\Resources\DropDownListResource;
use App\Http\Resources\CashReceiptGeneralResource;
use App\Http\Resources\AccountedAutoListResource;
use App\Http\Resources\AccountedFastListResource;
use App\Http\Model\AccObject;
use App\Http\Model\KeyAi;
use App\Http\Model\Error;
use App\Classes\Convert;
use App\Http\Model\AccNumberVoucher;
use Illuminate\Support\Facades\Validator;


class AccVoucherController extends Controller
{
  public function __construct(Request $request)
 {
   $key = explode("/",$request->headers->get('referer'));
   $this->url =  $request->segment(3);
   $this->key = $key[5];
   $this->menu = Menu::where('code', '=', $this->key)->first();
 }

  public function get(Request $request){
    $type = 10;
    try{
      $mysql2 = $request->session()->get('mysql2');
      config(['database.connections.mysql2' => $mysql2]);
      $req = json_decode($request->data);
      $data = ObjectDropDownListResource::collection(AccObject::get_type($req->filter_type));
        if ($req->filter_value != "" && $req->filter_value != null){
          $data = $data->filter(function($d) use ($req){
                  return str_contains($d[$req->filter_field], $req->filter_value);
              })->values();
        };
      if($req && $data->count()>0 ){
        return response()->json(['status'=>true,'data'=> $data]);
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
        return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
      }
  }


  public function bind(Request $request){
    $type = 10;
    try{
      $mysql2 = $request->session()->get('mysql2');
      config(['database.connections.mysql2' => $mysql2]);
      $req = json_decode($request->data);
      $data = new CashReceiptGeneralResource(AccGeneral::get_data_load_all($req));
      if($req && $data->count()>0 ){
        return response()->json(['status'=>true,'data'=> $data]);
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
        return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
      }
  }

  public function auto(Request $request){
    $type = 10;
    try{
      $mysql2 = $request->session()->get('mysql2');
      config(['database.connections.mysql2' => $mysql2]);
      $req = json_decode($request->data);
      $data = new AccountedAutoListResource(AccAccountedAuto::get_id_with_detail($req));
      if($req && $data->count()>0 ){
        return response()->json(['status'=>true,'data'=> $data]);
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
        return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
      }
  }


  public function reference(Request $request){
    $type = 10;
    try{
      $mysql2 = $request->session()->get('mysql2');
      config(['database.connections.mysql2' => $mysql2]);
      $req = json_decode($request->data);
      $reference_array = [0 , $req->general_id ] ;
      $data = CashReceiptGeneralResource::collection(AccGeneral::get_data_load_between_reference($req->filter_voucher,$req->start_date,$req->end_date,$reference_array));
      if($req && $data->count()>0 ){
        return response()->json(['status'=>true,'data'=> $data]);
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
        return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
      }
  }

  public function voucher_change(Request $request){
    $type = 10;
    try{
      $mysql2 = $request->session()->get('mysql2');
      config(['database.connections.mysql2' => $mysql2]);
      $req = json_decode($request->data);
        $validator = Validator::make(collect($req)->toArray(),[
        'number' => ['required','min:0'],
        'length_number' => 'required',
        'prefix' => 'required',
        ]);
      if($validator->passes()){
      $data = AccNumberVoucher::find($req->id);
      $data->prefix = $req->prefix;
      $data->suffixes = $req->suffixes;
      $data->number = $req->number;
      $data->length_number = $req->length_number;
      $data->save();
        return response()->json(['status'=>true]);
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
        return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
      }
  }


  public function ai(Request $request){
    $type = 10;
    try{
      $req = json_decode($request->data);
      $keys = KeyAi::all();
      $locale = $request->segment(1);
      if($locale == "en"){
      $rs = $keys->filter(function ($value, $k) use ($req) {
          return strpos(strtolower($req) , strtolower($value->name_en)) !== false;
      });
      }else{
        $rs = $keys->filter(function ($value, $k) use ($req) {
          return strpos(strtolower($req) , strtolower($value->name)) !== false;
        });
      }
      if($rs->count()>0){
      $rs = $rs->first();
      if($locale == "en"){
        $end_key = trim(substr($req,strlen($rs->name_en)));
      }else{
        $end_key = trim(substr($req,strlen($rs->name)));
      }
      if($rs->content == 'subject'){
          $mysql2 = $request->session()->get('mysql2');
          config(['database.connections.mysql2' => $mysql2]);
          //$sa = explode(" ",$rs->name_en);
          //$field = str_replace(" ","_",trim(substr($rs->name_en,strlen($sa[0]))));
          $data = AccObject::where($rs->field,$end_key)->first();
          if($data){
            $data = new ObjectDropDownListResource($data);
            return response()->json(['status'=>true,'data'=> $data,'field'=>$rs->content]);
          }else{
            return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
          }
        }else if($rs->content == 'traders' || $rs->content == 'description'){
            return response()->json(['status'=>true,'data'=> $end_key ,'field'=>$rs->content]);
        }else if($rs->content == 'accounted_auto'){
          $mysql2 = $request->session()->get('mysql2');
          config(['database.connections.mysql2' => $mysql2]);
          $data = AccAccountedAuto::where($rs->field,$end_key)->first();
          if($data){
            $data = new DropDownListResource($data);
            return response()->json(['status'=>true,'data'=> $data,'field'=>$rs->content]);
          }else{
            return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
          }
        }else if($rs->content == 'date'){
          $data = [];
          if($locale == "en"){
            $data = date('d/m/Y', strtotime($end_key));
          }else{
            $pattern = '/[0-9]+/';
            preg_match_all($pattern, $end_key,$arr);
            if(count($arr[0])>2){
              $d = $arr[0];
              $data = date('d/m/Y',strtotime("$d[2]/$d[1]/$d[0]"));
            }else{
              return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
            }
          };
          return response()->json(['status'=>true,'data'=> $data,'field'=>$rs->content]);
        }else if($rs->content == 'accounted_fast'){
          $mysql2 = $request->session()->get('mysql2');
          config(['database.connections.mysql2' => $mysql2]);
          $data = new AccountedFastListResource(AccAccountedFast::where($rs->field,$end_key)->first());
          if($data){
            return response()->json(['status'=>true,'data'=> $data,'field'=>$rs->content]);
          }else{
            return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
          }
        }else if($rs->content == 'debit' || $rs->content == 'credit'){
          $mysql2 = $request->session()->get('mysql2');
          config(['database.connections.mysql2' => $mysql2]);
          if($locale == "en"){
            $sa = explode($rs->crit_en,$end_key);
          }else{
            $sa = explode($rs->crit,$end_key);
          }
          $data = AccAccountSystems::where($rs->field,$sa[0])->first();
          $row = 0;
          if(count($sa)>1){
            $row = $sa[1];
          };
          if($data){
            $data = new DropDownListResource($data);
            return response()->json(['status'=>true,'data'=> $data,'row' => $row,'field'=>$rs->content]);
          }else{
            return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
          }
        }else if($rs->content == 'amount'){
          if($locale == "en"){
            $sa = explode($rs->crit_en,$end_key);
          }else{
            $sa = explode($rs->crit,$end_key);
          }
          $row = 0;
          $data = Convert::intDefaultformat($sa[0]);
          if(count($sa)>1){
            $row = $sa[1];
          };
          return response()->json(['status'=>true,'data'=> $data,'row' => $row,'field'=>$rs->content]);
        }else if($rs->content == 'add_row'){
          return response()->json(['status'=>true,'data'=> $rs,'field'=>$rs->content]);
        }else if($rs->content == 'copy_row'){
          return response()->json(['status'=>true,'data'=> $end_key,'field'=>$rs->content]);
        }else if($rs->content == 'remove_row'){
          return response()->json(['status'=>true,'data'=> $end_key,'field'=>$rs->content]);
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
        return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
      }
  }

  public function currency(Request $request){
    $type = 10;
    try{
      $mysql2 = $request->session()->get('mysql2');
      config(['database.connections.mysql2' => $mysql2]);
      $req = json_decode($request->data);
      $data = AccCurrency::find($req);
      if($req && $data->count()>0 ){
        return response()->json(['status'=>true,'data'=> $data->rate]);
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
        return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
      }
  }

}
