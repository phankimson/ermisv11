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
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BankLoadReadResource;
use App\Http\Model\Imports\AccBankReconciliationDetailImport;
use App\Http\Model\Imports\AccBankReconciliationGeneralImport;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class AccBankReconciliationController extends Controller
{
  protected $url;
  protected $key;
  protected $menu;
  protected $group;
  protected $page_system;
  public function __construct(Request $request)
  {
     $this->url =  $request->segment(3);
     $this->key = "bank-reconciliation";   
     $this->group = [3,4]; // 3,4 Thu chi bank 
     $this->menu = Menu::where('code', '=', $this->key)->first();
  }

  public function show(){
    return view('acc.bank_reconciliation',['key' => $this->key ]);
  }

  public function load(Request $request){
    $type = 10;
    try{
      $req = json_decode($request->data);
      $data1 = BankLoadReadResource::collection(AccGeneral::get_data_load_group_bank_account_between($this->group,$req->start_date,$req->end_date,$req->bank_account,$req->active));
      if($data1->count()>0){
        return response()->json(['status'=>true,'data1'=> $data1]);
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
        $bank_account = AccBankAccount::find($rs->bank_account);
        $bank = AccBank::find($bank_account->bank_id);
        $start_row = 0;
        $row = [];
        if($bank->name == "Vietinbank"){
          $start_row = 26;
          $row = ['accounting_date'=>1,'transaction_description'=>2,'debit_amount'=>3,'credit_amount'=>4,'transaction_number'=>6,'corresponsive_account'=>7,'corresponsive_name'=>8];
          $row_gen = ['bank_account'=>'C12','total_credit'=>'C21','total_debit  '=>'E21'];
        }
        $general = new AccBankReconciliationGeneralImport($row_gen);
        Excel::import($general, $file); 
        $data = new AccBankReconciliationDetailImport($rs->bank_account,$start_row,$row);
        Excel::import($data , $file);       
        return response()->json(['status'=>true,'message'=> trans('messages.success_import')]);
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

}
