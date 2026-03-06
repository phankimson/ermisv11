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

class AccPeriodController extends Controller
{
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
          $filter_sql = Convert::filterRow($filter);
          $arr = AccPeriod::get_raw_skip_filter_page($skip,$perPage,$orderby,$asc,$filter_sql);
          $total = AccPeriod::whereRaw($filter_sql)->count();
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
       // Láº¥y ká»³ thÃ¡ng trÆ°á»›c
       $period_last = AccPeriod::latest('created_at')->first();
       //$period_last = AccPeriod::get_date($formatLastMonth,1);
       // Láº¥y tá»•ng sá»‘ ká»³
       $period_count = AccPeriod::count();
     if($check && $check->id != $arr->id){
       return response()->json(['status'=>false,'message'=> trans('messages.duplicate_date')]);
     //}else if(!$period_last && $period_count>0){
     //   return response()->json(['status'=>false,'message'=> trans('messages.must_lock_the_previous_period').' @ '. $formatLastMonth]);  
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
         $data->name = "KhÃ³a ká»³ ".$arr->date;
         $data->name_en = "Lock period ".$arr->date;
         $data->date = $formatMonth;
         $data->active = 1;
         $data->save();
       };               
       $save_detail = true;
       // LÆ°u lá»‹ch sá»­
       $h = new AccHistoryAction();
       $h ->create([
         'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
         'user' => Auth::id(),
         'menu' => $this->menu->id,
         'url'  => $this->url,
         'dataz' => \json_encode($data)]);

       // Láº¥y ID vÃ  vÃ  phÃ¢n loáº¡i ThÃªm
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
    // LÆ°u báº£ng chi tiáº¿t stk chá»‘t ká»³ theo thÃ¡ng
      // Láº¥y giÃ¡ trá»‹ phÃ¡t sinh trong ká»³
   $detail = $general->load('detail')->pluck('detail')->collapse()->values(); 
   // Tá»•ng phÃ¡t sinh Ná»£  theo tk
   $debit_sum = $detail->groupBy('debit')->map(function ($row) {
              return $row->sum('amount_rate');
   });
    // Tá»•ng phÃ¡t sinh CÃ³ theo tk
   $credit_sum = $detail->groupBy('credit')->map(function ($row) {
     return $row->sum('amount_rate');
     });
   $merged_account = $debit_sum->merge($credit_sum);    
   foreach ($merged_account as $key=>$item ){
    $do = 0; // Ná»£ Ä‘áº§u ká»³  
    $co = 0; // CÃ³ Ä‘áº§u ká»³    
    $de = 0; // Ná»£ cuá»‘i ká»³
    $ce = 0; // CÃ³ cuá»‘i ká»³
    $debit_sum_fi = $debit_sum[$key] ?? 0;// TÃ¬m sá»‘ tiá»n tÃ i khoáº£n ná»£
    $credit_sum_fi = $credit_sum[$key] ?? 0;// TÃ¬m sá»‘ tiá»n tÃ i khoáº£n cÃ³
    if($period_last){    
      // Láº¥y báº£ng chi tiáº¿t Ä‘Ã£ lÆ°u cá»§a ká»³ trÆ°á»›c (sá»‘ Ä‘áº§u ká»³)      
      $account_systems_balance = AccAccountBalance::get_account($period_last,$key);        
    }else{
      // Láº¥y sá»‘ dÆ° Ä‘áº§u ká»³
      $account_systems_balance = AccAccountBalance::get_account(0,$key);         
    };
    if($account_systems_balance){ // Náº¿u cÃ³ phÃ¡t sinh láº¥y ná»£ cÃ³
     $do = $account_systems_balance->debit_close;
     $co = $account_systems_balance->credit_close;
    }; 
   // TÃ­nh sá»‘ ná»£ cÃ³ cuá»‘i ká»³      
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

    // LÆ°u chi tiáº¿t NCC,KH chá»‘t ká»³ theo thÃ¡ng
   // Tá»•ng phÃ¡t sinh ná»£
    $subject_debit_sum =  $detail->whereNotIn('subject_id_debit', ['',0])->groupBy('subject_id_debit')->map(function ($row) {
               return $row->sum('amount_rate');
      });
     // Tá»•ng phÃ¡t sinh cÃ³
    $subject_credit_sum = $detail->whereNotIn('subject_id_credit', ['',0])->groupBy('subject_id_credit')->map(function ($row) {
               return $row->sum('amount_rate');
      }); 
    $subject_merged_account = $subject_debit_sum->merge($subject_credit_sum);
    foreach ($subject_merged_account as $key=>$item ){
     $do = 0; // Ná»£ Ä‘áº§u ká»³  
     $co = 0; // CÃ³ Ä‘áº§u ká»³    
     $de = 0; // Ná»£ cuá»‘i ká»³
     $ce = 0; // CÃ³ cuá»‘i ká»³
     $debit_sum_fi = $subject_debit_sum[$key]?? 0;// TÃ¬m sá»‘ tiá»n tÃ i khoáº£n ná»£
     $credit_sum_fi = $subject_credit_sum[$key]?? 0;// TÃ¬m sá»‘ tiá»n tÃ i khoáº£n cÃ³
     if($period_last){
       // Láº¥y báº£ng chi tiáº¿t Ä‘Ã£ lÆ°u cá»§a ká»³ trÆ°á»›c (sá»‘ Ä‘áº§u ká»³)
       $object_balance = AccObjectBalance::get_object($period_last,$key);        
     }else{
       // Láº¥y sá»‘ dÆ° Ä‘áº§u ká»³
       $object_balance = AccObjectBalance::get_object(0,$key);         
     };
     if($object_balance){ // Náº¿u cÃ³ phÃ¡t sinh láº¥y ná»£ cÃ³
       $do = $object_balance->debit_close;
       $co = $object_balance->credit_close;
       }
     // TÃ­nh sá»‘ ná»£ cÃ³ cuá»‘i ká»³
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


    // LÆ°u chi tiáº¿t NgÃ¢n hÃ ng chá»‘t ká»³ theo thÃ¡ng
   // Tá»•ng phÃ¡t sinh ná»£
    $bank_account_debit_sum =  $detail->whereNotIn('bank_account_debit', ['',0])->groupBy('bank_account_debit')->map(function ($row) {
               return $row->sum('amount_rate');
    });
     // Tá»•ng phÃ¡t sinh cÃ³
    $bank_account_credit_sum = $detail->whereNotIn('bank_account_credit', ['',0])->groupBy('bank_account_credit')->map(function ($row) {
               return $row->sum('amount_rate');
      }); 
    $bank_account_merged_account = $bank_account_debit_sum->merge($bank_account_credit_sum);
    foreach ($bank_account_merged_account as $key=>$item ){
     $do = 0; // Ná»£ Ä‘áº§u ká»³  
     $co = 0; // CÃ³ Ä‘áº§u ká»³    
     $de = 0; // Ná»£ cuá»‘i ká»³
     $ce = 0; // CÃ³ cuá»‘i ká»³
     $debit_sum_fi = $bank_account_debit_sum[$key]?? 0;// TÃ¬m sá»‘ tiá»n tÃ i khoáº£n ná»£
     $credit_sum_fi = $bank_account_credit_sum[$key]?? 0;// TÃ¬m sá»‘ tiá»n tÃ i khoáº£n cÃ³
     if($period_last){
       // Láº¥y báº£ng chi tiáº¿t Ä‘Ã£ lÆ°u cá»§a ká»³ trÆ°á»›c (sá»‘ Ä‘áº§u ká»³)
       $bank_account_balance = AccBankAccountBalance::get_bank_account($period_last,$key);        
     }else{
       // Láº¥y sá»‘ dÆ° Ä‘áº§u ká»³
       $bank_account_balance = AccBankAccountBalance::get_bank_account(0,$key);         
     };
     if($bank_account_balance){ // Náº¿u cÃ³ phÃ¡t sinh láº¥y ná»£ cÃ³
       $do = $bank_account_balance->debit_close;
       $co = $bank_account_balance->credit_close;
       }
     // TÃ­nh sá»‘ ná»£ cÃ³ cuá»‘i ká»³
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

   
   // LÆ°u tá»“n kho chá»‘t ká»³ theo thÃ¡ng
   $stock = AccStock::all();
   foreach($stock as $s){     
    $inventory_re = $general->where('stock_receipt',$s)->load('inventory')->pluck('inventory')->collapse()->values();  
     // Tá»•ng tiá»n phÃ¡t sinh nháº­p
    $amount_sum_receipt = $inventory_re->groupBy('item_id')->map(function ($row) {
                     return $row->sum('amount');
             }); 
      // Tá»•ng sá»‘ lÆ°á»£ng phÃ¡t sinh nháº­p
    $quantity_sum_receipt =  $inventory_re->groupBy('item_id')->map(function ($row) {
                     return $row->sum('quantity');
             }); 
             
    $inventory_is = $general->where('stock_issue',$s)->load('inventory')->pluck('inventory')->collapse()->values();    
     // Tá»•ng tiá»n phÃ¡t sinh xuáº¥t      
    $amount_sum_issue = $inventory_is->groupBy('item_id')->map(function ($row) {
                 return $row->sum('amount');
         }); 
     // Tá»•ng sá»‘ lÆ°á»£ng phÃ¡t sinh xuáº¥t    
    $quantity_sum_issue = $inventory_is->groupBy('item_id')->map(function ($row) {
                 return $row->sum('quantity');
       });  
    $inventory_merged = $amount_sum_receipt->merge($amount_sum_issue);
    foreach ($inventory_merged as $key=>$item ){
       $ao = 0; // Sá»‘ tiá»n Ä‘áº§u ká»³  
       $qo = 0; // Sá»‘ lÆ°á»£ng Ä‘áº§u ká»³  
       $ae = 0; // Sá»‘ tiá»n cuá»‘i ká»³  
       $qe = 0; // Sá»‘ lÆ°á»£ng cuá»‘i ká»³ 
       $amount_sum_re = $amount_sum_receipt[$key]?? 0;// TÃ¬m sá»‘ tiá»n nháº­p
       $quantity_sum_re = $quantity_sum_receipt[$key]?? 0;// TÃ¬m sá»‘ lÆ°á»£ng nháº­p
       $amount_sum_is = $amount_sum_issue[$key]?? 0;// TÃ¬m sá»‘ tiá»n xuáº¥t
       $quantity_sum_is = $quantity_sum_issue[$key]?? 0;// TÃ¬m sá»‘ lÆ°á»£ng xuáº¥t
     if($period_last){          
       $stock_item_balance = AccStockBalance::get_item($period_last,$s,$key);         
     }else{
       $stock_item_balance = AccStockBalance::get_item(0,$s,$key);
     };
     if($stock_item_balance){ // Náº¿u cÃ³ phÃ¡t sinh láº¥y sá»‘ lÆ°á»£ng, giÃ¡ trá»‹
       $ao = $stock_item_balance->amount_close;
       $qo = $stock_item_balance->quantity_close;
     }      
     // TÃ­nh sá»‘ tá»“n cuá»‘i ká»³
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
           // LÆ°u lá»‹ch sá»­
           $h = new AccHistoryAction();
           $h ->create([
           'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
           'user' => Auth::id(),
           'menu' => $this->menu->id,
           'url'  => $this->url,
           'dataz' => \json_encode($data)]);                       
           // XÃ³a chi tiáº¿t stk
           $data->account_balance()->delete();
           // XÃ³a chi tiáº¿t NCC,KH
           $data->object_balance()->delete();  
           // XÃ³a chi tiáº¿t tá»“n kho
           $data->stock_balance()->delete(); 
           // XÃ³a ká»³
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
