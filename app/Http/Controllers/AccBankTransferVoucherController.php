<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\Menu;
use App\Http\Model\AccGeneral;
use App\Http\Model\AccDetail;
use App\Http\Model\AccPeriod;
use App\Http\Model\AccSystems;
use App\Http\Model\AccNumberVoucher;
use App\Http\Model\AccPrintTemplate;
use App\Http\Resources\BankTransferGeneralReadResource;
use App\Http\Model\Imports\AccBankTransferGeneralImport;
use App\Http\Model\Imports\AccBankTransferVoucherImport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\CurrencyCheckTraits;
use App\Http\Traits\FileAttachTraits;
use App\Http\Traits\NumberVoucherTraits;
use App\Http\Traits\ReferenceTraits;
use App\Http\Traits\AccHistoryTraits;

class AccBankTransferVoucherController extends Controller
{
  use AccHistoryTraits,CurrencyCheckTraits,FileAttachTraits,NumberVoucherTraits,ReferenceTraits;
  protected $url;
  protected $key;
  protected $menu;
  protected $group;
  protected $print;
  protected $document;
  protected $path;
  protected $check_cash;
  protected $download;
  public function __construct(Request $request)
 {
     $this->url =  $request->segment(3);
     $this->group = 11; // Nhom chuyen ngan hang noi bo
     $this->key = "bank-transfer-voucher";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->print = 'BT%';
     $this->path = 'PATH_UPLOAD_BANK_TRANSFER';
     $this->check_cash = 'CHECK_CASH';
     $this->download = 'AccBankTransferVoucher.xlsx';
 }

  public function show(){
    $voucher = AccNumberVoucher::get_menu($this->menu->id);
    $menu_tab =  Menu::get_menu_like_code($this->key.'%');
    //$setting_voucher = AccSettingVoucher::get_menu($this->menu->id);
    //$debt_default = new AccountSystemsDropDownListResource(AccAccountSystems::find($setting_voucher->debit));
    //if($setting_voucher->credit == 0){
    //  $credit_default = collect(['id' => 0 ,'code' => '---SELECT---','name' => '---SELECT---']);
    //}else{
    //  $credit_default = new AccountSystemsDropDownListResource(AccAccountSystems::find($setting_voucher->credit));
    //};
    //$work_code = json_encode(LangDropDownListResource::collection(AccWorkCode::active()->orderBy('code','asc')->get()));
    //$sys = AccSystems::get_systems($this->document);
    //$document = Document::get_code($sys->value);
    //$debt_account = json_encode(AccountSystemsDropDownListResource::collection(AccAccountSystems::get_wherein_id($document->id,$setting_voucher->debit_filter)));
    //$credit_account = json_encode(AccountSystemsDropDownListResource::collection(AccAccountSystems::get_wherein_id($document->id,$setting_voucher->credit_filter)));
    //$department = json_encode(LangDropDownListResource::collection(AccDepartment::active()->orderBy('code','asc')->get()));
    //$bank_account = json_encode(BankAccountDropDownListResource::collection(AccBankAccount::active()->orderBy('bank_account','asc')->get()));
    //$case_code = json_encode(LangDropDownListResource::collection(AccCaseCode::active()->orderBy('code','asc')->get()));
    //$cost_code = json_encode(LangDropDownListResource::collection(AccCostCode::active()->orderBy('code','asc')->get()));
    //$statistical_code = json_encode(LangDropDownListResource::collection(AccStatisticalCode::active()->orderBy('code','asc')->get()));
    //$accounted_fast = json_encode(AccountedFastDropDownListResource::collection(AccAccountedFast::active()->orderBy('code','asc')->get()));
    //$vat = json_encode(LangTaxDropDownListResource::collection(AccVat::active()->orderBy('code','asc')->get()));
    //$subject_code = json_encode(ObjectDropDownListResource::collection(AccObject::active()->orderBy('code','asc')->get()));
    $voucher_list = AccNumberVoucher::all();
    $print = AccPrintTemplate::get_code($this->print);
    return view('acc.'.str_replace("-", "_", $this->key),[ 'key' => $this->key , 'voucher' => $voucher, 'menu'=>$this->menu->id, 'menu_tab' => $menu_tab,                                    
                                        'voucher_list' => $voucher_list ,
                                        'print' => $print]);
  }



  public function save(Request $request){
    $type = 0;
    $action = '';
    try{
      DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
      $arr = json_decode($request->data);
      if($arr){
         $period = AccPeriod::get_date(Carbon::parse($arr->accounting_date)->format('Y-m'),1);
        if(!$period){
          $general = [];
          $removeId = [];
          $removeId_v = [];
          $permission = $request->session()->get('per');
          $check_permission = true;
          $user = Auth::user();
          if($permission['e'] == true && $arr->id ){
            $general = AccGeneral::find($arr->id);
             if(!$general){
              return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
            }
            $v = $general->voucher;
            $type = 3;
            $action = 'update';
          }else if($permission['a'] == true && !$arr->id){
            $type = 2;
            $action = 'add';
            $general = new AccGeneral();
            $general->user = $user->id;
            // Luu so nhay
               $v = $this->saveNumberVoucher($this->menu,$arr);
          }else{
            $check_permission = false;
          }
          $general->type = $this->menu->id;
          $general->voucher = $v;
          $general->currency = $arr->currency;
          $general->rate = $arr->rate;
          $general->description = $arr->description;
          $general->voucher_date = $arr->voucher_date;
          $general->accounting_date = $arr->accounting_date;
          $general->reference = $arr->reference;
          $general->total_amount = $arr->total_amount;
          $general->total_amount_rate = $arr->total_amount_rate;
          $general->status = 1;
          $general->active = 1;
          $general->group = $this->group;
          $general->save();
          
          // Tham chieu / Reference
          $this->saveReference($arr->reference_by,$general->id);

              // Lay gia tri kiem tra tien mat co am khong
          $ca = AccSystems::get_systems($this->check_cash);
          $acc = "";

          // CHI TIET / Detail
           foreach($arr->detail as $k => $d){
             $detail = collect([]);
             if($d->id){
               $detail = AccDetail::find($d->id);
                if(!$detail){
                  DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
                  return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
                }
             }else{
               $detail = new AccDetail();
             }
             $detail->general_id = $general->id;
             $detail->description = $d->description;
             $detail->currency = $arr->currency;
             $detail->debit = $d->debit->value;  // Doi tu id value dang read
             $detail->credit = $d->credit->value;  // Doi tu id value dang read
             $detail->amount = $d->amount;
             $detail->rate = $d->rate;
             $detail->amount_rate = $d->amount * $d->rate;
             $detail->accounted_fast = $d->accounted_fast->value;  // Doi tu id value dang read
             $detail->bank_account_debit = $arr->bank_account_debit;  // Doi tu id value dang read   
             $detail->bank_account_credit = $arr->bank_account_credit;  // Doi tu id value dang read             
             $detail->active = 1;
             $detail->status = 1;
             $detail->save();
       
             array_push($removeId,$detail->id);
             $arr->detail[$k]->id = $detail->id;      
             // Luu so ton tien ben No
             if(substr($d->debit->text,0,3) === '112'){ 
               $balance = $this->increaseCurrency($d->debit->value,$arr->currency,$d->amount,$d->rate,$arr->bank_account_debit);    
              //  $balance = AccCurrencyCheck::get_type_first($d->debit->value,$arr->currency,$d->bank_account_debit->value);     
              //  if($balance){
              //    $balance->amount = $balance->amount + ($d->amount * $d->rate);
              //    $balance->save();
              //  }else{
              //    $balance = new AccCurrencyCheck();
              //    $balance->type = $d->debit->value;
              //    $balance->currency = $arr->currency;
              //    $balance->bank_account = $d->bank_account_debit->value;
              //    $balance->amount = $d->amount * $d->rate;
              //    $balance->save();
              //  }
             }
               // End
           
               // Luu so ton tien ben Co
              if(substr($d->credit->text,0,3) == '112'){
                $balance = $this->reduceCurrency($d->credit->value,$arr->currency,$d->amount,$d->rate,$arr->bank_account_credit);
                //  $balance = AccCurrencyCheck::get_type_first($d->credit->value,$arr->currency,$d->bank_account_credit->value);     
                //  if($balance){
                //    $balance->amount = $balance->amount - ($d->amount * $d->rate);
                //    $balance->save();
                //  }else{                  
                //    $balance = new AccCurrencyCheck();
                //    $balance->type = $d->credit->value;
                //    $balance->currency = $arr->currency;
                //    $balance->bank_account = $d->bank_account_credit->value;
                //    $balance->amount = 0 - ($d->amount * $d->rate);
                //    $balance->save();
                //  }
                   if($ca->value == "1" && $balance->amount<0){
                    $acc = $d->credit->text;
                    break;
                  }
               }
               // End
           }

           // Xoa dong chi tiet
           AccDetail::get_detail_whereNotIn_delete($general->id,$removeId);
           
           // Luu file
           $this->saveFile($request,$general->id,$this->path);   

           // Luu lich su
           $this->create_history($type,Auth::id(),$this->menu->id,$this->url,$arr);
           
           if($acc != ""){
            DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
            return response()->json(['status'=>false,'message'=> trans('messages.account_negative',['account'=>$acc])]);
           }else if($check_permission == false){
            DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
             return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission')]);
           }else{
           DB::connection(env('CONNECTION_DB_ACC'))->commit();
           return response()->json(['status'=>true,'message'=> trans('messages.'.$action.'_success'), 'voucher_name' => $v , 'dataId' => $general->id ,  'data' => $arr ]);
           }
           //
      }else{
          return response()->json(['status'=>false,'message'=> trans('messages.locked_period')]);
      }
      }else{
          return response()->json(['status'=>false,'message'=> trans('messages.update_fail')]);
      }
    }catch(Exception $e){
      DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
      return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
    }
  }


  
  public function bind(Request $request){
    $type = 10;
    try{
      $req = json_decode($request->data);
      $data = new BankTransferGeneralReadResource(AccGeneral::get_data_load_all($req));
      if($req && $data->count()>0 ){
        return response()->json(['status'=>true,'data'=> $data]);
      }else{
        return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
      }
     }catch(Exception $e){
       return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
     }
  }

  public function DownloadExcel(){
    return Storage::download('public/downloadFile/'.$this->download);
  }


  public function import(Request $request) {
   $type = 5;
    try{
    $permission = $request->session()->get('per');
    if($permission['a'] && $request->hasFile('file')){
       if($request->file->getClientOriginalName() == $this->download){
      //Check
      $request->validate([
          'file' => 'required|mimeTypes:'.
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,'.
                'application/vnd.ms-excel',
      ]);
        //$rs = json_decode($request->data);
  
        $file = $request->file;
        //Chuyen du lieu Excel sang collect
        config(['excel.imports.read_only' => false]);
        $data = new AccBankTransferGeneralImport($this->menu);
        Excel::import($data , $file);
        $detail = new AccBankTransferVoucherImport($this->menu);
        Excel::import($detail, $file); 
        $merged = collect($data->getData())->push($detail->getData());            
        return response()->json(['status'=>true,'message'=> trans('messages.success_import'),'data'=>$merged]);
          }else{
      return response()->json(['status'=>false,'message'=> trans('messages.incorrect_file')]);
    }    
      }else{
        return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
      }
    }catch(Exception $e){
      DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
      return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__, 'messages.failed_import');
    }
  }


}
