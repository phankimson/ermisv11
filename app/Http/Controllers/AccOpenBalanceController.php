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
use App\Http\Model\AccSettingAccountGroup;
use App\Http\Model\AccSystems;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\AccStockBalance;
use App\Http\Model\AccSuppliesGoods;
use App\Http\Model\AccSuppliesGoodsType;
use App\Http\Model\AccCurrency;
use App\Http\Model\AccObject;
use App\Http\Model\AccObjectType;
use App\Http\Resources\OpenBalanceResource;
use App\Http\Resources\BankOpenBalanceResource;
use App\Http\Resources\SuppliesGoodsOpenBalanceResource;
use App\Http\Resources\ObjectOpenBalanceResource;
use App\Http\Model\Exports\AccAccountSystemsBalanceExport;
use App\Http\Model\Exports\AccBankAccountBalanceExport;
use App\Http\Model\Exports\AccStockBalanceExport;
use App\Http\Model\Imports\AccOpenBalanceAccountImport;
use App\Http\Model\Imports\AccOpenBalanceBankImport;
use App\Http\Model\Imports\AccOpenBalanceStockImport;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\LoadDocumentTraits;
use App\Http\Traits\CurrencyCheckTraits;
use App\Http\Traits\StockCheckTraits;

class AccOpenBalanceController extends Controller
{
  use LoadDocumentTraits;
  use CurrencyCheckTraits;
  use StockCheckTraits;
  protected $url;
  protected $key;
  protected $menu;
  protected $page_system;
  protected $download;
  protected $code_bank;
  protected $document;
  protected $currency_default;
  public function __construct(Request $request)
  {
     $this->url =  $request->segment(3);
     $this->key = "open-balance";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->page_system = "MAX_COUNT_CHANGE_PAGE";
     $this->download = "AccOpenBalance";
     $this->code_bank = "NH";
     $this->document = "DOCUMENT_TAX";
     $this->currency_default = "CURRENCY_DEFAULT";  
 }

  public function show(){
    $count = AccAccountBalance::count();
    $sys_page = AccSystems::get_systems($this->page_system);
    $paging = $count>$sys_page->value?1:0;   
    return view('acc.'.str_replace("-", "_", $this->key),['paging' => $paging, 'key' => $this->key ]);
  }

  public function data(Request $request){  
    $type = $request->input('type',null);
    $stock = $request->input('stock',null);
    // Lấy default document
    $document = $this->getDoc($this->document);  
    if($type == "account"){
    $data = OpenBalanceResource::collection(AccAccountSystems::get_with_balance_period($document->id,"0"));    
    }else if($type == "bank"){
    $setting = AccSettingAccountGroup::get_code($this->code_bank);
    $account_default = AccAccountSystems::find($setting->account_default);
    $data = BankOpenBalanceResource::customCollection(AccBankAccount::get_with_balance_period("0"),$account_default->code);
    }else if($type == "materials" || $type == "goods" || $type == "tools" || $type == "upfront_costs" || $type == "assets" || $type == "finished_product"){
      if($type == "materials"){
        $i = 1;
      }else if($type == "goods"){
        $i = 2;
      }else if($type == "tools"){
        $i = 3;
      }else if($type == "finished_product"){
        $i = 4;
      }else if($type == "upfront_costs"){
        $i = 6;
      }else if($type == "assets"){
        $i = 7;
      }else{
        $i = 0;
      } 
      if($i>0){
      $ty = AccSuppliesGoodsType::get_filter($i);
      $type_id = $ty->id;
      $account_default = AccAccountSystems::find($ty->account_default);       
      }else{
        $type_id = null;
        $account_default= null;
      }   
    $data = SuppliesGoodsOpenBalanceResource::customCollection(AccSuppliesGoods::get_with_balance_period("0",$type_id,$stock),$account_default?$account_default->code:null); 
    }else if($type == "suppliers" || $type == "customers" || $type == "employees" || $type == "others"){
      if($type == "suppliers"){
        $i = 1;
      }else if($type == "customers"){
        $i = 2; 
      }else if($type == "employees"){ 
        $i = 3;
      } else if($type == "others"){
        $i = 4; 
      }else{
        $i = 0;
      }
      if($i>0){
      $ty = AccObjectType::get_filter($i);
      $type_id = $ty->id;
      $account_default = AccAccountSystems::find($ty->account_default);
       }else{
        $type_id = null;
        $account_default= null;
      }
      $data = ObjectOpenBalanceResource::customCollection(AccObject::get_with_balance_period("0",$type_id),$account_default?$account_default->code:null); 
    }else{
      $data = null;
    }     
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
      if($rq->type == "bank"){
         // Kiểm tra có đúng với số dư tk không
        $check_balance = true;
        $check_account = "";
        $co = collect($arr);
          $re = $co->groupBy('account_default')->map(function ($group, $account_default) {
             return [
                  'account_default' => $account_default,
                  'debit_balance' => $group->sum('debit_balance'),
                  'credit_balance' => $group->sum('credit_balance'),
              ];
          })->values();
           foreach($re as $item){  
              $acc = AccAccountSystems::get_code($this->getId($this->document),$item['account_default']);
              $acc_balance = AccAccountBalance::get_account(0,$acc->id);
              $acc_balance_debit = $acc_balance?$acc_balance->debit_close:0;
              $acc_balance_credit = $acc_balance?$acc_balance->credit_close:0;
              if($item['debit_balance'] != $acc_balance_debit || $item['credit_balance'] != $acc_balance_credit){                            
                  $check_balance = false;     
                  $check_account .= $item['account_default'].", ";                        
              }
          };
          if($check_balance == false){
            return response()->json(['status'=>false,'message'=> trans('messages.account_details_do_not_match_balance_sheet',['account'=>$check_account])]);
          }
          //
      }else if($rq->type == "materials" || $rq->type == "goods" || $rq->type == "tools" || $rq->type == "upfront_costs" || $rq->type == "assets" || $rq->type == "finished_product"){
          // Kiểm tra có đúng với số dư tk không
          $check_balance = true;
          $check_quantity = true;
          $check_account = "";
          $co = collect($arr);
            $re = $co->groupBy('account_default')->map(function ($group, $account_default) {
              return [
                    'supplies_goods_list' =>$group->pluck('id'),
                    'account_default' => $account_default,
                    'quantity' => $group->sum('quantity'),
                    'amount' => $group->sum('amount'),
                ];
            })->values();
            foreach($re as $item){  
                $acc = AccAccountSystems::get_code($this->getId($this->document),$item['account_default']);
                $acc_balance = AccAccountBalance::get_account(0,$acc['id']);
                $acc_balance_amount = $acc_balance?$acc_balance->debit_close:0;
                $acc_balance_stock_amount = 0;
                 foreach($item['supplies_goods_list'] as $i){
                  $acc_balance_stock = AccStockBalance::get_sum_supplies_goods(0,$i);
                  if($acc_balance_stock>0){
                    $acc_balance_stock_amount += $acc_balance_stock;
                  }                  
                }
                if( ($item['amount'] + $acc_balance_stock_amount) > $acc_balance_amount){                            
                    $check_balance = false;     
                    $check_account .= $item['account_default'].", ";                    
                }
                if($item['amount'] == 0 && $item['quantity'] > 0){
                  $check_quantity = false;     
                  $check_account .= $item['account_default'].", "; 
                }
            };
            if($check_balance == false){
              return response()->json(['status'=>false,'message'=> trans('messages.account_details_do_not_match_balance_sheet',['account'=>$check_account])]);
           }else if($check_quantity == false){
              return response()->json(['status'=>false,'message'=> trans('messages.amount_cannot_be_zero',['account'=>$check_account])]);
           }else{
             
           }
      }else{

      }
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
        $currency_default = AccSystems::get_systems($this->currency_default);
        $rate = AccCurrency::get_code($currency_default->value);       
        foreach($arr as $k => $a){        
          $acc = AccAccountSystems::get_code($this->getId($this->document),$a->account_default);
          if($permission['a'] == true && !$a->balance_id ){
            $type = 2;
            $data = new AccBankAccountBalance();
            $data->period = 0;
            $data->bank_account = $a->id;              
          }else if($permission['e'] == true && $a->balance_id){
            $type = 3;
            $data = AccBankAccountBalance::find($a->balance_id);
             // Trả lại số dư tiền tệ
            if($data->debit_close >0){
              $this->reduceCurrency($acc->id,$rate->id,$data->debit_close,$rate->rate,$a->id);
            }
            if($data->credit_close >0){             
              $this->increaseCurrency($acc->id,$rate->id,$data->credit_close,$rate->rate,$a->id);
            }
            //
          }else{
            $check_perrmission = false;
          }
          if($a->debit_balance>0 || $a->credit_balance>0){           
            $data->debit_close = $a->debit_balance;
            $data->credit_close = $a->credit_balance;
            $data->save();   
            // Cập nhật số dư tiền tệ
            if($a->debit_balance>0){
               $this->increaseCurrency($acc->id,$rate->id,$a->debit_balance,$rate->rate,$a->id);
            }  
            if($a->credit_balance>0){
               $this->reduceCurrency($acc->id,$rate->id,$a->credit_balance,$rate->rate,$a->id);
            }
            //
            // Lưu lại id vào array
            $a->balance_id = $data->id;
            // Lưu vào collect mới
            $rs[$k] = $a;
          }            
        }
        
      }else if($rq->type == "materials" || $rq->type == "goods" || $rq->type == "tools" || $rq->type == "upfront_costs" || $rq->type == "assets" || $rq->type == "finished_product"){
        foreach($arr as $k => $a){ 
          $acc = AccAccountSystems::get_code($this->getId($this->document),$a->account_default);       
          if($permission['a'] == true && !$a->balance_id ){
            $type = 2;
            $data = new AccStockBalance();
            $data->period = 0;
            $data->stock = $rq->stock;
            $data->supplies_goods  = $a->id;  
          }else if($permission['e'] == true && $a->balance_id){
            $type = 3;
            $data = AccStockBalance::find($a->balance_id);
              // Trả lại số dư kho
              if($data->quantity_close >0){             
                $this->reduceStock($acc->id,$rq->stock,$a->id,$data->quantity);
              }
             //
          }else{
            $check_perrmission = false;
          }
          if($a->quantity>0 || $a->amount>0){
            $data->quantity_close = $a->quantity;
            $data->amount_close = $a->amount;
            $data->save();
              // Lưu lại số dư kho
                if($a->quantity >0){             
                  $this->increaseStock($acc->id,$rq->stock,$a->id,$a->quantity);
                }
              //
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


  public function export(Request $request) {
   $type = 6;
   try{
      $arr = $request->data;
      $page = $request->page;
      $arr_type = $request->type;
     // Lấy default document
      $document = $this->getDoc($this->document);  
       //return (new HistoryActionExport($arr))->download('HistoryActionExportErmis.xlsx');
       //$myFile = Excel::download(new HistoryActionExport($arr), 'HistoryActionExportErmis.xlsx');
       if($arr_type == "account"){
        $myFile = Excel::raw(new AccAccountSystemsBalanceExport($arr,$page,$document->id), \Maatwebsite\Excel\Excel::XLSX);
        $name = 'AccAccountSystems';
       }else if($arr_type == "bank"){
         $myFile = Excel::raw(new AccBankAccountBalanceExport($arr,$page), \Maatwebsite\Excel\Excel::XLSX);
         $name = 'AccBankAccount';
       }else if($arr_type == "materials" || $arr_type == "goods" || $arr_type == "tools" || $arr_type == "upfront_costs" || $arr_type == "assets" || $arr_type == "finished_product"){
         $myFile = Excel::raw(new AccStockBalanceExport($arr,$page), \Maatwebsite\Excel\Excel::XLSX);
         $name = 'Acc'.ucfirst($arr_type);
       }else{
        $myFile = '';
        $name = '';
       }       
       $response =  array(
         'status' =>true,
         'name' => $name."BalanceExportErmis", //no extention needed
         'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($myFile) //mime type of used format
      );
      return response()->json($response);
   }catch(Exception $e){
     // Lưu lỗi
     $err = new Error();
     $err ->create([
       'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
       'user_id' => Auth::id(),
       'menu_id' => $this->menu->id,
       'error' => $e->getMessage().' - Line '.$e->getLine(),
       'url'  => $this->url,
       'check' => 0 ]);
     return response()->json(['status'=>false,'message'=> trans('messages.failed_export').' '.$e->getMessage().' - Line '.$e->getLine()]);
   }
 }

  public function DownloadExcel(Request $request){
   $type = $request->input('type',null);
   if($type == "materials" || $type == "goods" || $type == "tools" || $type == "upfront_costs" || $type == "assets" || $type == "finished_product"){
         $type = 'stock';
   }
   return Storage::download('public/downloadFile/'.$this->download.ucfirst($type).'.xlsx');
 }

  public function import(Request $request) {
  $type = 5;
   try{
    DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
   $permission = $request->session()->get('per');
   $rs = json_decode($request->data);
   if($permission['a'] && $request->hasFile('file')){
         if($request->file->getClientOriginalName() == $this->download.ucfirst($rs->type).'.xlsx'){
     //Check
     $request->validate([
         'file' => 'required|mimeTypes:'.
               'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,'.
               'application/vnd.ms-excel',
     ]);
  
       $file = $request->file;
       // Import dữ liệu
       if($rs->type == "account"){
        //config(['excel.imports.read_only' => false]);
        $import = new AccOpenBalanceAccountImport;
        Excel::import($import , $file);
        $arr = $import->getData();
        foreach($arr as $k => $a){
            $type = 3;        
            $data = AccAccountBalance::get_account(0,$a['id']);
            if(!$data){
              $data = new AccAccountBalance();
              $data->period = 0;
              $data->account_systems = $a['id'];  
              $type = 2;
            }           
            $data->debit_close = $a['debit_balance'];
            $data->credit_close = $a['credit_balance'];
            $data->save();
            // Lưu lại id vào array
            $a['balance_id'] = $data->id;
            // Lưu vào collect mới
            $arr[$k] = $a;
        };
         // Lấy lại dữ liệu  
       $rs->arr =  $arr;  
       }else if($rs->type == "bank"){
        $import = new AccOpenBalanceBankImport;
        Excel::import($import , $file);
        $arr = $import->getData();
        $currency_default = AccSystems::get_systems($this->currency_default);
        $rate = AccCurrency::get_code($currency_default->value);    
        $setting = AccSettingAccountGroup::get_code($this->code_bank);
        $account_default = AccAccountSystems::find($setting->account_default);
        foreach($arr as $k => $a){
            $type = 3;        
            $data = AccBankAccountBalance::get_bank_account(0,$a['id']);
            if(!$data){
              $data = new AccBankAccountBalance();
              $data->period = 0;
              $data->bank_account = $a['id'];  
              $type = 2;
            }           
            $data->debit_close = $a['debit_balance'];
            $data->credit_close = $a['credit_balance'];
            $data->save();
            $acc = $a['account_default']?$a['account_default']:$account_default->id;
              // Cập nhật số dư tiền tệ
            if($a->debit_balance>0){
               $this->increaseCurrency($acc,$rate->id,$a['debit_balance'],$rate->rate,$a->id);
            }  
            if($a->credit_balance>0){
               $this->reduceCurrency($acc,$rate->id,$a['credit_balance'],$rate->rate,$a->id);
            }
            //
            // Lưu lại id vào array
            $a['balance_id'] = $data->id;
            // Lưu vào collect mới
            $arr[$k] = $a;
        };
         // Lấy lại dữ liệu  
       $rs->arr =  $arr;  
       }else if($rs->type == "materials" || $rs->type == "goods" || $rs->type == "tools" || $rs->type == "upfront_costs" || $rs->type == "assets" || $rs->type == "finished_product"){
         $import = new AccOpenBalanceStockImport;
          Excel::import($import , $file);
          $arr = $import->getData();
          foreach($arr as $k => $a){
            $type = 3;        
            $data = AccStockBalance::get_supplies_goods(0,$a['id'],$rs->stock);
            if(!$data){
              $data = new AccStockBalance();
              $data->period = 0;
              $data->supplier_goods = $a['id'];  
              $data->stock = $rs->stock;  
              $type = 2;
            }           
            $data->quantity_close = $a['quantity_close'];
            $data->amount_close = $a['amount_close'];
            $data->save();
                // Lấy giá trị mặc định      
                if($type == "materials"){
                  $i = 1;
                }else if($type == "goods"){
                  $i = 2;
                }else if($type == "tools"){
                  $i = 3;
                }else if($type == "finished_product"){
                  $i = 4;
                }else if($type == "upfront_costs"){
                  $i = 6;
                }else if($type == "assets"){
                  $i = 7;
                }else{
                  $i = 0;
                } 
                if($i>0){
                $ty = AccSuppliesGoodsType::get_filter($i);
                $account_default = AccAccountSystems::find($ty->account_default);       
                }else{
                  $account_default= null;
                } 
            $supplies_goods = AccSuppliesGoods::find($a['id']);
            $acc = $supplies_goods?$supplies_goods->stock_account:($account_default?$account_default->id:null);
            // Lưu lại số dư kho
                if($a->quantity >0){             
                  $this->increaseStock($acc,$rs->stock,$data->supplier_goods,$data->quantity);
                }
              //
           
            // Lưu lại id vào array
            $a['balance_id'] = $data->id;
            // Lưu vào collect mới
            $arr[$k] = $a;
        };
       }else{
         $import = '';
       }        
       $merged = collect($rs);
       //dump($merged);
     // Lưu lịch sử
     $h = new AccHistoryAction();
     $h ->create([
       'type' => $type, // Add : 2 , Edit : 3 , Delete : 4, Import : 5
       'user' => Auth::id(),
       'menu' => $this->menu->id,
       'url'  => $this->url,
       'dataz' => \json_encode($merged)]);
     //
     //Storage::delete($savePath.$filename);
     DB::connection(env('CONNECTION_DB_ACC'))->commit();
     broadcast(new \App\Events\DataSendArrayTabs($merged));
     return response()->json(['status'=>true,'message'=> trans('messages.success_import')]);
   }else{
    return response()->json(['status'=>false,'message'=> trans('messages.incorrect_file')]);
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
       'error' => $e->getMessage().' - Line '.$e->getLine(),
       'url'  => $this->url,
       'check' => 0 ]);
     return response()->json(['status'=>false,'message'=> trans('messages.failed_import').' '.$e->getMessage().' - Line '.$e->getLine()]);
   }
 }


}
