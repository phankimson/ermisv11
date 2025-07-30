<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\Error;
use App\Http\Model\AccGeneral;
use App\Http\Model\Menu;
use App\Http\Model\AccBankAccount;
use App\Http\Model\AccBank;
use App\Http\Model\AccPeriod;
use App\Http\Model\AccDetail;
use App\Http\Model\AccBankCompare;
use App\Http\Model\AccHistoryAction;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BankLoadReadResource;
use App\Http\Resources\BankCompareLoadReadResource;
use App\Http\Resources\BankCompareCreateGeneralVoucherResource;
use App\Http\Model\Imports\AccBankCompareDetailImport;
use App\Http\Model\Imports\AccBankCompareGeneralImport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Exception;

class AccBankCompareController extends Controller
{
  protected $url;
  protected $key;
  protected $menu;
  protected $group;
  protected $page_system;
  public function __construct(Request $request)
  {
     $this->url =  $request->segment(3);
     $this->key = "bank-compare";   
     $this->group = [3,4]; // 3,4 Thu chi bank 
     $this->menu = Menu::where('code', '=', $this->key)->first();
  }

  public function show(){
    return view('acc.bank_compare',['key' => $this->key ]);
  }

  public function load(Request $request){
    $type = 10;
    try{
      $req = json_decode($request->data);
      $data1 = BankLoadReadResource::collection(AccGeneral::get_data_load_group_bank_account_between($this->group,$req->start_date,$req->end_date,$req->bank_account,$req->active));
      $data2 = BankCompareLoadReadResource::collection(AccBankCompare::get_data_load_between($req->bank_account,$req->start_date,$req->end_date));
      if($data1->count()>0){
        return response()->json(['status'=>true,'data1'=> $data1,'data2'=>$data2]);
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

  public function check(Request $request) {
    $type = 3;
       try{
        DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
         $permission = $request->session()->get('per');
         $arr = json_decode($request->data);
         if($arr){
           if($permission['e'] == true){
             $tab1 = $arr->tab1;
             if(count($tab1)>0){
             $data = AccDetail::find($tab1[0]);
             $period = AccPeriod::get_date(Carbon::parse($data->accounting_date)->format('Y-m'),1);
             if(!$period){
               // Lưu lịch sử
               $h = new AccHistoryAction();
               $h ->create([
               'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
               'user' => Auth::id(),
               'menu' => $this->menu->id,
               'url'  => $this->url,
               'dataz' => \json_encode($arr)]);

               //DETAIL
               foreach($arr->tab1 as $k){
                  $d = AccDetail::find($k);                 
                  $d->status = 2;
                  $d->save();
               }
               //compare
               foreach($arr->tab2 as $l){
                  $d = AccBankCompare::find($l);
                  $d->status = 2;
                  $d->save();
               }              
                DB::connection(env('CONNECTION_DB_ACC'))->commit();
               return response()->json(['status'=>true,'message'=> trans('messages.check_success')]);
             }
             
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
         return response()->json(['status'=>false,'message'=> trans('messages.check_fail').' '.$e->getMessage()]);
       }
  }

  public function uncheck(Request $request) {
    $type = 3;
       try{
        DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
         $permission = $request->session()->get('per');
         $arr = json_decode($request->data);
         if($arr){
           if($permission['e'] == true){
             $tab1 = $arr->tab1;
             if(count($tab1)>0){
             $data = AccDetail::find($tab1[0]);
             $period = AccPeriod::get_date(Carbon::parse($data->accounting_date)->format('Y-m'),1);
             if(!$period){
               // Lưu lịch sử
               $h = new AccHistoryAction();
               $h ->create([
               'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
               'user' => Auth::id(),
               'menu' => $this->menu->id,
               'url'  => $this->url,
               'dataz' => \json_encode($arr)]);

               //DETAIL
               foreach($arr->tab1 as $k){
                  $d = AccDetail::find($k);                 
                  $d->status = 1;
                  $d->save();
               }
               //compare
               foreach($arr->tab2 as $l){
                  $d = AccBankCompare::find($l);
                  $d->status = 1;
                  $d->save();
               }              
                DB::connection(env('CONNECTION_DB_ACC'))->commit();
               return response()->json(['status'=>true,'message'=> trans('messages.uncheck_success')]);
             }
             
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
         return response()->json(['status'=>false,'message'=> trans('messages.uncheck_fail').' '.$e->getMessage()]);
       }
  }

  
  public function import(Request $request) {
   $type = 5;
    try{
    $permission = $request->session()->get('per');
    if($permission['a'] && $request->hasFile('file')){
      //Check
      $request->validate([
          'file' => 'required|mimeTypes:'.
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,'.
                'application/vnd.ms-excel',
      ]);
        $rs = json_decode($request->data);
        $file = $request->file;
        // Đổi dữ liệu Excel sang collect
        config(['excel.imports.read_only' => false]);
        $bank_account = AccBankAccount::find($rs->crit);
        $bank = AccBank::find($bank_account->bank_id);
        if($bank->name == "Vietinbank"){
          $start_row = 26;
          $row = ['accounting_date'=>1,'transaction_description'=>2,'debit_amount'=>3,'credit_amount'=>4,'transaction_number'=>6,'corresponsive_account'=>7,'corresponsive_name'=>8];
          $row_gen = ['bank_account'=>'C12','total_credit'=>'C21','total_debit'=>'E21'];
          $general = new AccBankCompareGeneralImport($row_gen);
          Excel::import($general, $file); 
          $general_data = $general->getData();
          if($general_data['bank_account'] == $bank_account->bank_account){
            $data = new AccBankCompareDetailImport($rs->crit,$start_row,$row);
            Excel::import($data , $file);  
            $data_count = $data->getRowCount();
            return response()->json(['status'=>true,'message'=> trans('messages.success_import')." ".trans('messages.total_imported',['count_sucess' => $data_count])]);   
          }else{
            return response()->json(['status'=>true,'message'=> trans('messages.bank_account_not_correct')]);   
          }         
        }else{
          return response()->json(['status'=>true,'message'=> trans('messages.failed_import')]); 
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
      return response()->json(['status'=>false,'message'=> trans('messages.failed_import').' '.$e->getMessage()]);
    }
  }

   public function create_voucher(Request $request){
    $type = 10;
    try{
      $req = json_decode($request->data);
      $data = new BankCompareCreateGeneralVoucherResource(AccBankCompare::get_item_object($req));
      //$data = new BankCompareCreateGeneralVoucherResource(AccBankCompare::get_item_object($req))->DefaultValue('bar');
      if($data){
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
