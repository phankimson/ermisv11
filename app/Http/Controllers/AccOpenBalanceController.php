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
use App\Http\Model\Document;
use App\Http\Model\AccHistoryAction;
use App\Http\Resources\OpenBalanceResource;
use App\Http\Resources\BankOpenBalanceResource;
use App\Http\Model\Exports\AccAccountSystemsBalanceExport;
use App\Http\Model\Exports\AccBankAccountBalanceExport;
use App\Http\Model\Imports\AccOpenBalanceAccountImport;
use App\Http\Model\Imports\AccOpenBalanceBankImport;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class AccOpenBalanceController extends Controller
{
  protected $url;
  protected $key;
  protected $menu;
  protected $page_system;
  protected $download;
  protected $code_bank;
  protected $document;
  public function __construct(Request $request)
  {
     $this->url =  $request->segment(3);
     $this->key = "open-balance";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->page_system = "MAX_COUNT_CHANGE_PAGE";
     $this->download = "AccOpenBalance";
     $this->code_bank = "NH";
     $this->document = "DOCUMENT_TAX";
 }

  public function show(){
     $count = AccAccountBalance::count();
    $sys_page = AccSystems::get_systems($this->page_system);
    $paging = $count>$sys_page->value?1:0;   
    return view('acc.'.str_replace("-", "_", $this->key),['paging' => $paging, 'key' => $this->key ]);
  }

  public function data(Request $request){  
    $type = $request->input('type',null);
    if($type == "account"){
    $data = OpenBalanceResource::collection(AccAccountSystems::get_with_balance_period("0"));    
    }else if($type == "bank"){
    $sys = AccSystems::get_systems($this->document);
    $doc = Document::get_code($sys->value);    
    $setting = AccSettingAccountGroup::get_code($this->code_bank);
    $account_default = AccAccountSystems::get_code_like_first($doc->id,$setting->account_group);
    $data = BankOpenBalanceResource::collection(AccBankAccount::get_with_balance_period("0"));
    
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


  public function export(Request $request) {
   $type = 6;
   try{
      $arr = $request->data;
      $page = $request->page;
      $arr_type = $request->type;
       //return (new HistoryActionExport($arr))->download('HistoryActionExportErmis.xlsx');
       //$myFile = Excel::download(new HistoryActionExport($arr), 'HistoryActionExportErmis.xlsx');
       if($arr_type == "account"){
        $myFile = Excel::raw(new AccAccountSystemsBalanceExport($arr,$page), \Maatwebsite\Excel\Excel::XLSX);
        $name = 'AccAccountSystems';
       }else if($arr_type == "bank"){
         $myFile = Excel::raw(new AccBankAccountBalanceExport($arr,$page), \Maatwebsite\Excel\Excel::XLSX);
         $name = 'AccBankAccount';
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
            // Lưu lại id vào array
            $a['balance_id'] = $data->id;
            // Lưu vào collect mới
            $arr[$k] = $a;
        };
         // Lấy lại dữ liệu  
       $rs->arr =  $arr;  
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
     broadcast(new \App\Events\DataSendCollectionTabs($merged));
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
