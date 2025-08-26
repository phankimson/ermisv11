<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\Menu;
use App\Http\Model\Error;
use App\Http\Model\AccAccountBalance;
use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccBankAccount;
use App\Http\Model\AccBankAccountBalance;
use App\Http\Model\AccSystems;
use App\Http\Model\AccHistoryAction;
use App\Http\Resources\OpenBalanceResource;
use App\Http\Resources\BankOpenBalanceResource;
use Exception;
use Illuminate\Support\Facades\DB;

class AccOpenBalanceController extends Controller
{
  protected $url;
  protected $key;
  protected $menu;
  protected $page_system;
  protected $download;
  protected $nature_none;
  public function __construct(Request $request)
  {
     $this->url =  $request->segment(3);
     $this->key = "open-balance";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->page_system = "MAX_COUNT_CHANGE_PAGE";
     $this->download = "AccOpenBalance.xlsx";
 }

  public function show(){
     $count = AccAccountBalance::count();
    $sys_page = AccSystems::get_systems($this->page_system);
    $paging = $count>$sys_page->value?1:0;   
    return view('acc.'.str_replace("-", "_", $this->key),['paging' => $paging, 'key' => $this->key ]);
  }

  public function data_account(){  
    $data = OpenBalanceResource::collection(AccAccountSystems::get_with_balance_period("0"));       
    if($data){
      return response()->json($data);
    }else{
      return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
    }
  }

  public function data_bank(){  
    $data = BankOpenBalanceResource::collection(AccBankAccount::get_with_balance_period("0"));       
    if($data){
      return response()->json($data);
    }else{
      return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
    }
  }


   public function save(Request $request){   
    $type = 0;
    try{
      DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
      $permission = $request->session()->get('per');
      $rq = json_decode($request->data);
      $arr = $rq->dataSource;
      $rs = collect();
      $validator = Validator::make(collect($rq)->toArray(),[
            'type' => 'required',
        ]);
     $check_perrmission = true;
     if($validator->passes()){
      if($rq->type == "account"){
          foreach($arr as $k => $a){
          if($permission['a'] == true && !$a->balance_id ){
            $type = 2;
            $data = new AccAccountBalance();
            $data->period = 0;
            $data->account_systems = $a->id;  
          }else if($permission['e'] == true && $a->balance_id){
            $type = 3;
            $data = AccAccountBalance::find($a->balance_id);
          }else{
            $check_perrmission = false;
          }
          if($a->debit_balance>0 || $a->credit_balance>0){
            $data->debit_close = $a->debit_balance;
            $data->credit_close = $a->credit_balance;
            $data->save();
            // Lưu lại id vào array
            $a->balance_id = $data->id;
            // Lưu vào collect mới
            $rs[$k] = $a;
          }            
        }
      }else if($rq->type == "bank"){
        foreach($arr as $k => $a){        
          if($permission['a'] == true && !$a->balance_id ){
            $type = 2;
            $data = new AccBankAccountBalance();
            $data->period = 0;
            $data->bank_account = $a->id;  
          }else if($permission['e'] == true && $a->balance_id){
            $type = 3;
            $data = AccBankAccountBalance::find($a->balance_id);
          }else{
            $check_perrmission = false;
          }
          if($a->debit_balance>0 || $a->credit_balance>0){
            $data->debit_close = $a->debit_balance;
            $data->credit_close = $a->credit_balance;
            $data->save();
            // Lưu lại id vào array
            $a->balance_id = $data->id;
            // Lưu vào collect mới
            $rs[$k] = $a;
          }            
        }


      }else{

      }
       
      // Xóa phần datasource cho đỡ nặng
      unset($rq->dataSource);
      // Cho vào array để đẩy lại event realtime
      $rq->arr = $rs;
       // Lưu lịch sử
        $h = new AccHistoryAction();
        $h ->create([
          'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
          'user' => Auth::id(),
          'menu' => $this->menu->id,
          'url'  => $this->url,
          'dataz' => \json_encode($arr)]);
        //
      if($check_perrmission == true){
        broadcast(new \App\Events\DataSendCollectionTabs($rq));
        DB::connection(env('CONNECTION_DB_ACC'))->commit();
        return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
      }else{
         DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
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
        'error' => $e->getMessage().' - Line '.$e->getLine(),
        'url'  => $this->url,
        'check' => 0 ]);
      return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage().' - Line '.$e->getLine()]);
    }
 }


}
