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
use App\Http\Model\CompanySoftware;
use App\Http\Model\Company;
use App\Http\Model\Error;
use App\Http\Model\AccSystems;
use App\Classes\Convert;
use App\Http\Model\AccStock;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use DB;

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
    return view('acc.period',['paging' => $paging, 'key' => $this->key ]);
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
    $type = 0;
    try{
      DB::beginTransaction();
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
         }; 
         if($general->count()>0){
           // Lấy kỳ gần nhất
         $period_last = AccPeriod::latest('id')->first();

         // Lưu bảng chi tiết stk chốt kỳ theo tháng
           // Lấy giá trị phát sinh trong kỳ
        $debit_sum = $general->loadSum(['detail' => function (Builder $query) { $query->groupBy('debit'); }], 'amount_rate' ); // Tổng phát sinh nợ
        $debit_account = $debit_sum->detail()->pluck('debit');
        $credit_sum = $general->loadSum(['detail'=> function (Builder $query) { $query->groupBy('credit'); }], 'amount_rate' ); // Tổng phát sinh có
        $credit_account = $credit_sum->detail()->pluck('credit');
        $merged_account = $debit_account->merge($credit_account);
        foreach ($merged_account as $item ){
         $do = 0; // Nợ đầu kỳ  
         $co = 0; // Có đầu kỳ    
         $de = 0; // Nợ cuối kỳ
         $ce = 0; // Có cuối kỳ
         $debit_sum_fi = $debit_sum->detail()->firstWhere('debit',$item);// Tìm số tiền tài khoản nợ
         $credit_sum_fi = $credit_sum->detail()->firstWhere('credit',$item);// Tìm số tiền tài khoản có
         if($period_last){
           // Lấy bảng chi tiết đã lưu của kỳ trước (số đầu kỳ)
           $account_systems_balance = $period_last->account_balance()->where('account_systems',$item);
           $do = $account_systems_balance->debit_close;
           $co = $account_systems_balance->credit_close;
         }else{
           // Lấy số dư đầu kỳ
           $account_systems_balance = AccAccountBalance::get_account(0,$item);
           $do = $account_systems_balance->debit_open;
           $co = $account_systems_balance->credit_open;
         };
         $de = max($do - $co + $debit_sum_fi - $credit_sum_fi,0) ;
         $ce = max($co - $do - $debit_sum_fi + $credit_sum_fi,0 );
               $arr = [
                 'period' => $data->id,
                 'account_systems' => $item,
                 'debit_open' => $do,
                 'credit_open' => $co,
                 'debit' => $debit_sum_fi,
                 'credit' => $credit_sum_fi,
                 'debit_close' => $de,
                 'credit_close' => $ce,
             ];     
             $data->account_balance()->create($arr);
        };

         // Lưu chi tiết NCC,KH chốt kỳ theo tháng
         $subject_debit_sum = $general->loadSum(['detail'=> function (Builder $query) { $query->groupBy('subject_id_debit'); }], 'amount_rate' ); // Tổng phát sinh nợ
         $subject_debit_account = $subject_debit_sum->detail()->pluck('debit');
         $subject_credit_sum = $general->loadSum(['detail' => function (Builder $query) { $query->groupBy('subject_id_credit'); }], 'amount_rate' ); // Tổng phát sinh có
         $subject_credit_account = $subject_credit_sum->detail()->pluck('credit');
         $subject_merged_account = $subject_debit_account->merge($subject_credit_account);
         foreach ($subject_merged_account as $item ){
          $do = 0; // Nợ đầu kỳ  
          $co = 0; // Có đầu kỳ    
          $de = 0; // Nợ cuối kỳ
          $ce = 0; // Có cuối kỳ
          $debit_sum_fi = $subject_debit_sum->detail()->firstWhere('debit',$item);// Tìm số tiền tài khoản nợ
          $credit_sum_fi = $subject_credit_sum->detail()->firstWhere('credit',$item);// Tìm số tiền tài khoản có
          if($period_last){
            // Lấy bảng chi tiết đã lưu của kỳ trước (số đầu kỳ)
            $account_systems_balance = $period_last->object_balance()->where('object',$item);
            $do = $account_systems_balance->debit_close;
            $co = $account_systems_balance->credit_close;
          }else{
            // Lấy số dư đầu kỳ
            $account_systems_balance = AccAccountBalance::get_object(0,$item);
            $do = $account_systems_balance->debit_open;
            $co = $account_systems_balance->credit_open;
          };
          $de = max($do - $co + $debit_sum_fi - $credit_sum_fi,0) ;
          $ce = max($co - $do - $debit_sum_fi + $credit_sum_fi,0 );
                $arr = [
                  'period' => $data->id,
                  'object' => $item,
                  'debit_open' => $do,
                  'credit_open' => $co,
                  'debit' => $debit_sum_fi,
                  'credit' => $credit_sum_fi,
                  'debit_close' => $de,
                  'credit_close' => $ce,
              ];     
              $data->object_balance()->create($arr);
         }; 

        // Lưu tồn kho chốt kỳ theo tháng
        $stock = AccStock::all();
        foreach($stock as $s){
         $ao = 0; // Số tiền đầu kỳ  
         $qo = 0; // Số lượng đầu kỳ  
         $ae = 0; // Số tiền cuối kỳ  
         $qe = 0; // Số lượng cuối kỳ  
         $amount_sum_receipt = $general->where('stock_receipt',$s)->loadSum(['inventory'=> function (Builder $query) { $query->groupBy('item_id'); }], 'amount' ); // Tổng tiền phát sinh nhập
         $number_sum_receipt = $general->where('stock_receipt',$s)->loadSum(['inventory' => function (Builder $query) { $query->groupBy('item_id'); }], 'quantity' ); // Tổng số lượng phát sinh nhập
         $amount_sum_issue = $general->where('stock_issue',$s)->loadSum(['inventory' => function (Builder $query) { $query->groupBy('item_id'); }], 'amount' ); // Tổng tiền phát sinh xuất
         $number_sum_issue = $general->where('stock_issue',$s)->loadSum(['inventory' => function (Builder $query) { $query->groupBy('item_id'); }], 'quantity'); // Tổng số lượng phát sinh xuất
         if($period_last){
           $stock_item_balance = $period_last->stock_balance()->where('supplies_goods',$item);
         }else{

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
         DB::commit();
         broadcast(new \App\Events\DataSend($merge));
         return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
       }

     }else{
        return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission')]);
       }
     }else{
       DB::rollBack();
       return response()->json(['status'=>false,'error'=>$validator->getMessageBag()->toArray() ,'message'=>trans('messages.error')]);
     }
    }catch(Exception $e){
      DB::rollBack();
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
        DB::beginTransaction();
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
            DB::commit();
            broadcast(new \App\Events\DataSend($arr));
            return response()->json(['status'=>true,'message'=> trans('messages.delete_success')]);
          }else{
            return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission_delete')]);
          }
       }else{
         return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
       }
      }catch(Exception $e){
        DB::rollBack();
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
