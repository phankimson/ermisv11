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
use App\Http\Model\Error;
use App\Http\Model\AccSystems;
use App\Classes\Convert;
use App\Http\Model\AccStock;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class AccPeriodReController extends Controller
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
    return view('acc.period_re',['paging' => $paging, 'key' => $this->key ]);
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
      $request->session()->put(env('CONNECTION_DB_ACC'), $params);
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
  $type = 0;
  try{
    DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
    $permission = $request->session()->get('per');
    $arr = json_decode($request->data);
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
       // Lấy kỳ tháng trước
       $period_last = AccPeriod::latest('created_at')->first();
       //$period_last = AccPeriod::get_date($formatLastMonth,1);
       // Lấy tổng số kỳ
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
         $data->name = "Khóa kỳ ".$arr->date;
         $data->name_en = "Lock period ".$arr->date;
         $data->date = $formatMonth;
         $data->active = 1;
         $data->save();
       }; 
       if($general->count()>0){       
       // Lưu bảng chi tiết stk chốt kỳ theo tháng
         // Lấy giá trị phát sinh trong kỳ
      $detail = $general->load('detail')->pluck('detail')->collapse()->values(); 
      // Tổng phát sinh Nợ  theo tk
      $debit_sum = $detail->groupBy('debit')->map(function ($row) {
                 return $row->sum('amount_rate');
      });
       // Tổng phát sinh Có theo tk
      $credit_sum = $detail->groupBy('credit')->map(function ($row) {
        return $row->sum('amount_rate');
        });
      $merged_account = $debit_sum->merge($credit_sum);    
      foreach ($merged_account as $key=>$item ){
       $do = 0; // Nợ đầu kỳ  
       $co = 0; // Có đầu kỳ    
       $de = 0; // Nợ cuối kỳ
       $ce = 0; // Có cuối kỳ
       $debit_sum_fi = $debit_sum[$key] ?? 0;// Tìm số tiền tài khoản nợ
       $credit_sum_fi = $credit_sum[$key] ?? 0;// Tìm số tiền tài khoản có
       if($period_last){    
         // Lấy bảng chi tiết đã lưu của kỳ trước (số đầu kỳ)      
         $account_systems_balance = AccAccountBalance::get_account($period_last->id,$key);        
       }else{
         // Lấy số dư đầu kỳ
         $account_systems_balance = AccAccountBalance::get_account(0,$key);         
       };
       if($account_systems_balance){ // Nếu có phát sinh lấy nợ có
        $do = $account_systems_balance->debit_close;
        $co = $account_systems_balance->credit_close;
       }; 
      // Tính số nợ có cuối kỳ      
       $de = max($do - $co + $debit_sum_fi - $credit_sum_fi,0) ;
       $ce = max($co - $do - $debit_sum_fi + $credit_sum_fi,0 );
             $arr = [
               'period' => $data->id,
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

       // Lưu chi tiết NCC,KH chốt kỳ theo tháng
      // Tổng phát sinh nợ
       $subject_debit_sum =  $detail->groupBy('subject_id_debit')->map(function ($row) {
                  return $row->sum('amount_rate');
         });
        // Tổng phát sinh có
       $subject_credit_sum = $detail->groupBy('subject_id_credit')->map(function ($row) {
                  return $row->sum('amount_rate');
         }); 
       $subject_merged_account = $subject_debit_sum->merge($subject_credit_sum);
       foreach ($subject_merged_account as $key=>$item ){
        $do = 0; // Nợ đầu kỳ  
        $co = 0; // Có đầu kỳ    
        $de = 0; // Nợ cuối kỳ
        $ce = 0; // Có cuối kỳ
        $debit_sum_fi = $subject_debit_sum[$key];// Tìm số tiền tài khoản nợ
        $credit_sum_fi = $subject_credit_sum[$key];// Tìm số tiền tài khoản có
        if($period_last){
          // Lấy bảng chi tiết đã lưu của kỳ trước (số đầu kỳ)
          $object_balance = AccObjectBalance::get_account($period_last->id,$key);        
        }else{
          // Lấy số dư đầu kỳ
          $object_balance = AccObjectBalance::get_object(0,$key);         
        };
        if($object_balance){ // Nếu có phát sinh lấy nợ có
          $do = $object_balance->debit_close;
          $co = $object_balance->credit_close;
          }
        // Tính số nợ có cuối kỳ
        $de = max($do - $co + $debit_sum_fi - $credit_sum_fi,0) ;
        $ce = max($co - $do - $debit_sum_fi + $credit_sum_fi,0 );
              $arr = [
                'period' => $data->id,
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

      // Lưu tồn kho chốt kỳ theo tháng
      $stock = AccStock::all();
      foreach($stock as $s){     
       $inventory_re = $general->where('stock_receipt',$s)->load('inventory')->pluck('inventory')->collapse()->values();  
        // Tổng tiền phát sinh nhập
       $amount_sum_receipt = $inventory_re->groupBy('item_id')->map(function ($row) {
                        return $row->sum('amount');
                }); 
         // Tổng số lượng phát sinh nhập
       $quantity_sum_receipt =  $inventory_re->groupBy('item_id')->map(function ($row) {
                        return $row->sum('quantity');
                }); 
                
       $inventory_is = $general->where('stock_issue',$s)->load('inventory')->pluck('inventory')->collapse()->values();    
        // Tổng tiền phát sinh xuất      
       $amount_sum_issue = $inventory_is->groupBy('item_id')->map(function ($row) {
                    return $row->sum('amount');
            }); 
        // Tổng số lượng phát sinh xuất    
       $quantity_sum_issue = $inventory_is->groupBy('item_id')->map(function ($row) {
                    return $row->sum('quantity');
          });  
       $inventory_merged = $amount_sum_receipt->merge($amount_sum_issue);
       foreach ($inventory_merged as $key=>$item ){
          $ao = 0; // Số tiền đầu kỳ  
          $qo = 0; // Số lượng đầu kỳ  
          $ae = 0; // Số tiền cuối kỳ  
          $qe = 0; // Số lượng cuối kỳ 
          $amount_sum_re = $amount_sum_receipt[$key];// Tìm số tiền nhập
          $quantity_sum_re = $quantity_sum_receipt[$key];// Tìm số lượng nhập
          $amount_sum_is = $amount_sum_issue[$key];// Tìm số tiền xuất
          $quantity_sum_is = $quantity_sum_issue[$key];// Tìm số lượng xuất
        if($period_last){          
          $stock_item_balance = AccStockBalance::get_item($period_last->id,$s,$key);         
        }else{
          $stock_item_balance = AccStockBalance::get_item(0,$s,$key);
        };
        if($stock_item_balance){ // Nếu có phát sinh lấy số lượng, giá trị
          $ao = $stock_item_balance->amount_close;
          $qo = $stock_item_balance->quantity_close;
        }      
        // Tính số tồn cuối kỳ
        $ae =  $ao + $amount_sum_re -$amount_sum_is ;
        $qe = $qo + $quantity_sum_re - $quantity_sum_is;
              $arr = [
                'period' => $data->id,
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
       DB::connection(env('CONNECTION_DB_ACC'))->commit();
       broadcast(new \App\Events\DataSend($merge));
       return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
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
  $type = 4;
     try{
      DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
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
           // Xóa chi tiết stk
           $data->account_balance()->delete();
           // Xóa chi tiết NCC,KH
           $data->object_balance()->delete();  
           // Xóa chi tiết tồn kho
           $data->stock_balance()->delete(); 
           // Xóa kỳ
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
