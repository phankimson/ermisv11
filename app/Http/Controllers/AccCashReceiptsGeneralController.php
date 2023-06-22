<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\User;
use App\Http\Model\Menu;
use App\Http\Model\AccGeneral;
use App\Http\Model\AccDetail;
use App\Http\Model\AccSystems;
use App\Http\Model\AccPeriod;
use App\Http\Model\AccCurrencyCheck;
use App\Http\Model\CompanySoftware;
use App\Http\Model\Company;
use App\Http\Model\AccPrintTemplate;
use App\Http\Model\Error;
use App\Http\Resources\CashReceiptGeneralResource;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AccCashReceiptsGeneralController extends Controller
{
  public function __construct(Request $request)
 {
     $this->url =  $request->segment(3);
     $this->type = 1; // 1 Thu tiền mặt
     $this->key = "cash-receipts-general";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->print = 'PT%';
     $this->date_range = "DATE_RANGE_GENERAL";
     $this->action = ["new"=>"cash-receipts-voucher"];
 }

  public function show(Request $request){
    $mysql2 = $request->session()->get('mysql2');
    config(['database.connections.mysql2' => $mysql2]);
    $sys = AccSystems::get_systems($this->date_range);
    $end_date_default = Carbon::now();
    $start_date_default = Carbon::now()->subDays($sys->value);
    $data = AccGeneral::get_range_date(null,$this->type,$end_date_default,$start_date_default);
    $end_date = $end_date_default->format('d/m/Y');
    $start_date = $start_date_default->format('d/m/Y');
    $print = AccPrintTemplate::get_code($this->print);
    return view('acc.receipt_cash_general',['data' => $data, 'key' => $this->key, 'action' => $this->action , 'end_date' => $end_date ,'print' => $print, 'start_date'=>$start_date]);
  }


  public function unwrite(Request $request) {
    $mysql2 = $request->session()->get('mysql2');
    config(['database.connections.mysql2' => $mysql2]);
    $type = 3;
       try{
         $permission = $request->session()->get('per');
         $arr = json_decode($request->data);
         if($arr){
           if($permission['e'] == true){
             $data = AccGeneral::find($arr);
             $period = AccPeriod::get_date(Carbon::parse($data->accounting_date)->format('Y-m'),1);
             if(!$period){
               $detail = AccDetail::get_detail_active($data->id,1);

               // Lưu lịch sử
               $h = new AccHistoryAction();
               $h ->create([
               'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
               'user' => Auth::id(),
               'menu' => $this->menu->id,
               'url'  => $this->url,
               'dataz' => \json_encode($data)]);
               $data->active = 0;
               $data->save();

               //DETAIL
               $ty = 1 ;// 1 -> Cash , 2 -> Bank
               $detail->each(function ($d) use ($ty){
                    $d->update(['active'=>0]);
                    // Lưu số tồn
                    $ba = AccCurrencyCheck::get_type_first($ty,$d->currency,null);
                    if($ba){
                      $ba->amount = $ba->amount - $d->amount;
                      $ba->save();
                    }
                });

               return response()->json(['status'=>true,'message'=> trans('messages.unrecored_success')]);
             }else{
               return response()->json(['status'=>false,'message'=> trans('messages.locked_period')]);
             }

           }else{
             return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission_edit')]);
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
         return response()->json(['status'=>false,'message'=> trans('messages.unrecored_fail').' '.$e->getMessage()]);
       }
  }

  public function write(Request $request) {
    $mysql2 = $request->session()->get('mysql2');
    config(['database.connections.mysql2' => $mysql2]);
    $type = 3;
       try{
         $permission = $request->session()->get('per');
         $arr = json_decode($request->data);
         if($arr){
           if($permission['e'] == true){
             $data = AccGeneral::find($arr);
             $period = AccPeriod::get_date(Carbon::parse($data->accounting_date)->format('Y-m'),1);
             if(!$period){
               $detail = AccDetail::get_detail_active($data->id,0);
               // Lưu lịch sử
               $h = new AccHistoryAction();
               $h ->create([
               'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
               'user' => Auth::id(),
               'menu' => $this->menu->id,
               'url'  => $this->url,
               'dataz' => \json_encode($data)]);
               $data->active = 1;
               $data->save();

               //DETAIL
               $ty = 1 ;// 1 -> Cash , 2 -> Bank
               $detail->each(function ($d) use ($ty){
                    $d->update(['active'=>1]);
                    // Lưu số tồn
                    $ba = AccCurrencyCheck::get_type_first($ty,$d->currency,null);
                    if($ba){
                      $ba->amount = $ba->amount + $d->amount;
                      $ba->save();
                    }
                });

               return response()->json(['status'=>true,'message'=> trans('messages.unrecored_success')]);
             }else{
               return response()->json(['status'=>false,'message'=> trans('messages.locked_period')]);
             }

           }else{
             return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission_edit')]);
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
         return response()->json(['status'=>false,'message'=> trans('messages.recored_fail').' '.$e->getMessage()]);
       }
  }

  public function find(Request $request){
    $type = 10;
    try{
      $mysql2 = $request->session()->get('mysql2');
      config(['database.connections.mysql2' => $mysql2]);
      $req = json_decode($request->data);
      $data = collect(CashReceiptGeneralResource::collection(AccGeneral::get_data_load_between($this->type,$req->start_date_a,$req->end_date_a)));
      if($req->active != ""){
        $data = $data->where('active',$req->active)->values();
      }
      if($data->count()>0){
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

}
