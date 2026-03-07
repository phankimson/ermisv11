<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\Menu;
use App\Http\Model\AccPeriod;
use App\Http\Model\AccGeneral;
use App\Http\Model\AccAccountBalance;
use App\Http\Model\AccObjectBalance;
use App\Http\Model\AccStockBalance;
use App\Http\Model\CompanySoftware;
use App\Http\Model\Company;
use App\Http\Model\AccSystems;
use App\Classes\Convert;
use App\Http\Model\AccBankAccountBalance;
use App\Http\Model\AccStock;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\AccHistoryTraits;

class AccPeriodController extends Controller
{
  use AccHistoryTraits;
  protected $url;
  protected $key;
  protected $menu;
  protected $page_system;
  public function __construct(Request $request)
 {
     $this->url =  $request->segment(3);
     $this->key = "period";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->page_system = "MAX_COUNT_CHANGE_PAGE";
 }

  public function show(){
    //$data = AccPeriod::get_raw();
    $count = AccPeriod::count();
    $sys_page = AccSystems::get_systems($this->page_system);
    $paging = $count>$sys_page->value?1:0;   
    return view('acc.'.$this->key,['paging' => $paging, 'key' => $this->key ]);
  }

  
  public function data(Request $request){   
    $total = AccPeriod::count();
    $sys_page = AccSystems::get_systems($this->page_system);
    $perPage = $request->input('$top',$sys_page->value);
    $skip = $request->input('$skip',0);
    $orderby =   $request->input('$orderby','created_at desc');
    $filter =   $request->input('$filter');
    $asc  = 'desc';
        if (!str_contains($orderby, 'desc')) { 
          $asc = 'asc';
        }else{
          $orderby = explode(' ', $orderby)[0];
        };
        if($filter){
          $filter_conditions = Convert::parseFilterConditions($filter);
          if($filter_conditions === null){
            return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
          }
          $arr = AccPeriod::get_raw_skip_filter_page($skip,$perPage,$orderby,$asc,$filter_conditions);
          $total = Convert::applyFilterConditions(AccPeriod::query(), $filter_conditions)->count();
        }else{
          $arr = AccPeriod::get_raw_skip_page($skip,$perPage,$orderby,$asc); 
        }    
    $data = collect(['data' => $arr,'total' => $total]);              
    if($data){
      return response()->json($data);
    }else{
      return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
    }
  }

  public function ChangeDatabase(Request $request){
    $type = 9;
    try{
      $req = json_decode($request->data);
      $db = CompanySoftware::find($req->database);
      if(!$db){
        return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
      }
      $com = Company::find($db->company_id);
      if(!$com){
        return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
      }
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
      $request->session()->put(env('CONNECTION_DB_ACC'), $params);
      config(['database.connections.mysql2' => $params]);
      DB::purge('mysql2');
      DB::reconnect('mysql2');
      $data = AccPeriod::get_raw();
      return response()->json(['status'=>true,'data'=> $data,'com_name'=> $com->name ]);
    }catch(Exception $e){
      return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
    }
 }

 
 public function save(Request $request){
  $type = 0;
  try{
    DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
    $permission = $request->session()->get('per');
    $arr = json_decode($request->data);
    $save_detail = false;
    $validator = Validator::make(collect($arr)->toArray(),[
          'date' => ['required']
      ]);
   if($validator->passes()){
   if($permission['a'] == true){
     $formatDate = Carbon::createFromFormat('!m/Y',$arr->date);
     $formatMonth = $formatDate->format('Y-m');
     $formatLastMonth = $formatDate->copy()->subMonth()->format('Y-m');
     $check = AccPeriod::get_date($formatMonth,1);
     $startDate = $formatDate->startOfMonth()->format('Y-m-d');
     $endDate = $formatDate->endOfMonth()->format('Y-m-d');
     $general = AccGeneral::get_range_date($startDate,$endDate);
       // Lay ky thang truoc
       $period_last = AccPeriod::latest('created_at')->first();
       //$period_last = AccPeriod::get_date($formatLastMonth,1);
       // Lay tong so ky
       $period_count = AccPeriod::count();
     if($check && $check->id != $arr->id){
       return response()->json(['status'=>false,'message'=> trans('messages.duplicate_date')]);
     }else if(!$period_last && $period_count>0){
        return response()->json(['status'=>false,'message'=> trans('messages.must_lock_the_previous_period').' @ '. $formatLastMonth]);  
     }else if($general->where('active',0)->count()>0){
       $voucher = '';
       foreach($general->where('active',0) as $g){
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
       };               
       $save_detail = true;
       // Luu lich su
       $this->create_history($type,Auth::id(),$this->menu->id,$this->url,$data);

       // Lay ID va phan loai Them
       $merge = collect((array)$arr);
       $merge = json_decode($merge->merge($data->toArray())->toJson());
       $merge->t = $type;
       DB::connection(env('CONNECTION_DB_ACC'))->commit();
       broadcast(new \App\Events\DataSend($merge));
       return response()->json(['status'=>true,'message'=> trans('messages.update_success'),'general'=>$general->pluck('id'),'period_last'=>$period_last->id,'dataId'=>$data->id,'save_detail'=>$save_detail]);
     }

   }else{
      return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission')]);
     }
   }else{
      DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
     return response()->json(['status'=>false,'error'=>$validator->getMessageBag()->toArray() ,'message'=>trans('messages.error')]);
   }
  }catch(Exception $e){
    DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
    return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
  }
}

public function saveDetail(Request $request){
  $type = 0;
  try{
  DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
  $data = $request->all();
  $general_id = $data['general'];
  $period_last = $data['period_last'];
  $dataId = $data['dataId'];
  $general = AccGeneral::get_whereIn($general_id);
  if($general->count()>0){       
    // Luu bang chi tiet stk chat ky theo thang
      // Lay gia tri phat sinh trong ky
   $detail = $general->load('detail')->pluck('detail')->collapse()->values(); 
   // Tang phat sinh No  theo tk
   $debit_sum = $detail->groupBy('debit')->map(function ($row) {
              return $row->sum('amount_rate');
   });
    // Tang phat sinh Co  theo tk
   $credit_sum = $detail->groupBy('credit')->map(function ($row) {
     return $row->sum('amount_rate');
     });
   $merged_account = $debit_sum->merge($credit_sum);    
   foreach ($merged_account as $key=>$item ){
    $do = 0; // No dau ky  
    $co = 0; // Co dau ky    
    $de = 0; // No cuoi ky
    $ce = 0; // Co cuoi ky
    $debit_sum_fi = $debit_sum[$key] ?? 0;// Tim so tien tai khoan no
    $credit_sum_fi = $credit_sum[$key] ?? 0;// Tim so tien tai khoan co
    if($period_last){    
      // Lay bang chi tiet cua ky truoc (so du dau ky)      
      $account_systems_balance = AccAccountBalance::get_account($period_last,$key);        
    }else{
      // Lay so du dau ky
      $account_systems_balance = AccAccountBalance::get_account(0,$key);         
    };
    if($account_systems_balance){ // Neu co phat sinh lay no co
     $do = $account_systems_balance->debit_close;
     $co = $account_systems_balance->credit_close;
    }; 
   // Tinh so du cuoi ky      
    $de = max($do - $co + $debit_sum_fi - $credit_sum_fi,0) ;
    $ce = max($co - $do - $debit_sum_fi + $credit_sum_fi,0 );
          $arr = [
            'period' => $dataId,
            'account_systems' => $key,
            'debit_open' => $do,
            'credit_open' => $co,
            'debit' => $debit_sum_fi,
            'credit' => $credit_sum_fi,
            'debit_close' => $de,
            'credit_close' => $ce,
        ];
       AccAccountBalance::create($arr);
   };

    // Luu chi tiet NCC,KH chat ky theo thang
   // Tang phat sinh No
    $subject_debit_sum =  $detail->whereNotIn('subject_id_debit', ['',0])->groupBy('subject_id_debit')->map(function ($row) {
               return $row->sum('amount_rate');
      });
     // Tang phat sinh Co
    $subject_credit_sum = $detail->whereNotIn('subject_id_credit', ['',0])->groupBy('subject_id_credit')->map(function ($row) {
               return $row->sum('amount_rate');
      }); 
    $subject_merged_account = $subject_debit_sum->merge($subject_credit_sum);
    foreach ($subject_merged_account as $key=>$item ){
     $do = 0; // No dau ky  
     $co = 0; // Co dau ky    
     $de = 0; // No cuoi ky
     $ce = 0; // Co cuoi ky
     $debit_sum_fi = $subject_debit_sum[$key]?? 0;// Tim so tien tai khoan no
     $credit_sum_fi = $subject_credit_sum[$key]?? 0;// Tim so tien tai khoan co
     if($period_last){
       // Lay bang chi tiet cua ky truoc (so du dau ky)
       $object_balance = AccObjectBalance::get_object($period_last,$key);        
     }else{
       // Lay so du dau ky
       $object_balance = AccObjectBalance::get_object(0,$key);         
     };
     if($object_balance){ // Neu co phat sinh lay no co
       $do = $object_balance->debit_close;
       $co = $object_balance->credit_close;
       }
     // Tinh so du cuoi ky
     $de = max($do - $co + $debit_sum_fi - $credit_sum_fi,0) ;
     $ce = max($co - $do - $debit_sum_fi + $credit_sum_fi,0 );
           $arr = [
             'period' => $dataId,
             'object' => $key,
             'debit_open' => $do,
             'credit_open' => $co,
             'debit' => $debit_sum_fi,
             'credit' => $credit_sum_fi,
             'debit_close' => $de,
             'credit_close' => $ce,
         ];     
         AccObjectBalance::create($arr);
    }; 


    // Luu chi tiet Ngân hàng chat ky theo thang
   // Tang phat sinh No
    $bank_account_debit_sum =  $detail->whereNotIn('bank_account_debit', ['',0])->groupBy('bank_account_debit')->map(function ($row) {
               return $row->sum('amount_rate');
    });
     // Tang phat sinh Co
    $bank_account_credit_sum = $detail->whereNotIn('bank_account_credit', ['',0])->groupBy('bank_account_credit')->map(function ($row) {
               return $row->sum('amount_rate');
      }); 
    $bank_account_merged_account = $bank_account_debit_sum->merge($bank_account_credit_sum);
    foreach ($bank_account_merged_account as $key=>$item ){
     $do = 0; // No dau ky  
     $co = 0; // Co dau ky    
     $de = 0; // No cuoi ky
     $ce = 0; // Co cuoi ky
     $debit_sum_fi = $bank_account_debit_sum[$key]?? 0;// Tim so tien tai khoan no
     $credit_sum_fi = $bank_account_credit_sum[$key]?? 0;// Tim so tien tai khoan co
     if($period_last){
       // Lay bang chi tiet cua ky truoc (so du dau ky)
       $bank_account_balance = AccBankAccountBalance::get_bank_account($period_last,$key);        
     }else{
       // Lay so du dau ky
       $bank_account_balance = AccBankAccountBalance::get_bank_account(0,$key);         
     };
     if($bank_account_balance){ // Neu co phat sinh lay no co
       $do = $bank_account_balance->debit_close;
       $co = $bank_account_balance->credit_close;
       }
     // Tinh so du cuoi ky
     $de = max($do - $co + $debit_sum_fi - $credit_sum_fi,0) ;
     $ce = max($co - $do - $debit_sum_fi + $credit_sum_fi,0 );
           $arr = [
             'period' => $dataId,
             'bank_account' => $key,
             'debit_open' => $do,
             'credit_open' => $co,
             'debit' => $debit_sum_fi,
             'credit' => $credit_sum_fi,
             'debit_close' => $de,
             'credit_close' => $ce,
         ];     
         AccBankAccountBalance::create($arr);
    }; 

   
   // Luu ton kho chat ky theo thang
   $stock = AccStock::all();
   foreach($stock as $s){     
    $inventory_re = $general->where('stock_receipt',$s)->load('inventory')->pluck('inventory')->collapse()->values();  
     // Tang phat sinh Nhap
    $amount_sum_receipt = $inventory_re->groupBy('item_id')->map(function ($row) {
                     return $row->sum('amount');
             }); 
      // Tang so luong phat sinh Nhap
    $quantity_sum_receipt =  $inventory_re->groupBy('item_id')->map(function ($row) {
                     return $row->sum('quantity');
             }); 
             
    $inventory_is = $general->where('stock_issue',$s)->load('inventory')->pluck('inventory')->collapse()->values();    
     // Tang phat sinh Xuat      
    $amount_sum_issue = $inventory_is->groupBy('item_id')->map(function ($row) {
                 return $row->sum('amount');
         }); 
     // Tang so luong phat sinh Xuat    
    $quantity_sum_issue = $inventory_is->groupBy('item_id')->map(function ($row) {
                 return $row->sum('quantity');
       });  
    $inventory_merged = $amount_sum_receipt->merge($amount_sum_issue);
    foreach ($inventory_merged as $key=>$item ){
       $ao = 0; // So du dau ky tien
       $qo = 0; // So du dau ky luong
       $ae = 0; // So du cuoi ky tien
       $qe = 0; // So du cuoi ky luong
       $amount_sum_re = $amount_sum_receipt[$key]?? 0;// Tim so tien nhap
       $quantity_sum_re = $quantity_sum_receipt[$key]?? 0;// Tim so luong nhap
       $amount_sum_is = $amount_sum_issue[$key]?? 0;// Tim so tien xuat
       $quantity_sum_is = $quantity_sum_issue[$key]?? 0;// Tim so luong xuat
     if($period_last){          
       $stock_item_balance = AccStockBalance::get_item($period_last,$s,$key);         
     }else{
       $stock_item_balance = AccStockBalance::get_item(0,$s,$key);
     };
     if($stock_item_balance){ // Neu co phat sinh lay so du dau ky
       $ao = $stock_item_balance->amount_close;
       $qo = $stock_item_balance->quantity_close;
     }      
     // Tinh so du cuoi ky
     $ae =  $ao + $amount_sum_re -$amount_sum_is ;
     $qe = $qo + $quantity_sum_re - $quantity_sum_is;
           $arr = [
             'period' => $dataId,
             'stock' => $s,
             'supplies_goods ' => $key,  
             'quantity_open' => $ao,
             'amount_open' => $qo,
             'quantity_receipt' => $quantity_sum_re,
             'amount_receipt' => $amount_sum_re,
             'quantity_issue' => $quantity_sum_is,
             'amount_issue' => $amount_sum_is,
             'quantity_close' => $qe,
             'amount_close' => $ae,
         ];     
         AccStockBalance::create($arr);
   }
 } 
  DB::connection(env('CONNECTION_DB_ACC'))->commit();
   return response()->json(['status'=>true]);      
  }else{
   return response()->json(['status'=>false]);   
  }
}catch(Exception $e){
  DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
  return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
}
}

 
 public function delete(Request $request) {
  $type = 4;
     try{
      DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
       $permission = $request->session()->get('per');
       $arr = json_decode($request->data);
       if($arr){
         if($permission['d'] == true){
           $data = AccPeriod::find($arr->id);
           if(!$data){
            return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
          }
           // Luu lich su
             $this->create_history($type,Auth::id(),$this->menu->id,$this->url,$data); 
           // Xoa chi tiet stk
           $data->account_balance()->delete();
           // Xoa chi tiet NCC,KH
           $data->object_balance()->delete();  
           // Xoa chi tiet ton kho
           $data->stock_balance()->delete(); 
           // Xoa ky
           $data->delete();
           DB::connection(env('CONNECTION_DB_ACC'))->commit();
           broadcast(new \App\Events\DataSend($arr));
           return response()->json(['status'=>true,'message'=> trans('messages.delete_success')]);
         }else{
           return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission_delete')]);
         }
      }else{
        return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
      }
     }catch(Exception $e){
       DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
       return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__, 'messages.delete_fail');
     }
}

}
