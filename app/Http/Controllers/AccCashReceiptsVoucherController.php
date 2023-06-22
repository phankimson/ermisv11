<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\Menu;
use App\Http\Model\AccGeneral;
use App\Http\Model\AccDetail;
use App\Http\Model\AccVatDetail;
use App\Http\Model\AccAttach;
use App\Http\Model\AccPeriod;
use App\Http\Model\AccSystems;
use App\Http\Model\AccCurrencyCheck;
use App\Http\Model\AccSettingVoucher;
use App\Http\Model\AccNumberVoucher;
use App\Http\Model\AccPrintTemplate;
use App\Http\Model\AccWorkCode;
use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccDepartment;
use App\Http\Model\AccBankAccount;
use App\Http\Model\AccCaseCode;
use App\Http\Model\AccCostCode;
use App\Http\Model\AccStatisticalCode;
use App\Http\Model\AccAccountedFast;
use App\Http\Model\AccVat;
use App\Http\Resources\LangDropDownListResource;
use App\Http\Resources\LangTaxDropDownListResource;
use App\Http\Resources\BankAccountDropDownListResource;
use App\Http\Resources\AccountedFastDropDownListResource;
use App\Http\Resources\AccountSystemsDropDownListResource;
use App\Http\Resources\ObjectDropDownListResource;
use App\Http\Model\AccObject;
use App\Http\Model\Error;
use App\Classes\Convert;
use App\Http\Model\AccObjectType;
use App\Http\Model\Document;
use Carbon\Carbon;

class AccCashReceiptsVoucherController extends Controller
{
  public function __construct(Request $request)
 {
     $this->url =  $request->segment(3);
     $this->type = 1; // 1 Thu tiền mặt
     $this->type_object = 2; // 2 Khách hàng (VD : 2,3 nếu nhiều đối tượng)
     $this->key = "cash-receipts-voucher";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->print = 'PT%';
     $this->path = 'PATH_UPLOAD_CASH_RECEIPTS';
     $this->document = 'DOCUMENT_TAX';
 }

  public function show(Request $request){
    $mysql2 = $request->session()->get('mysql2');
    config(['database.connections.mysql2' => $mysql2]);
    $ot = AccObjectType::get_filter($this->type_object);
    $voucher = AccNumberVoucher::get_menu($this->menu->id);
    $setting_voucher = AccSettingVoucher::get_menu($this->menu->id);
    $debt_default = new AccountSystemsDropDownListResource(AccAccountSystems::find($setting_voucher->debit));
    if($setting_voucher->credit == 0){
      $credit_default = collect(['id' => 0 ,'code' => '---SELECT---','name' => '---SELECT---']);
    }else{
      $credit_default = new AccountSystemsDropDownListResource(AccAccountSystems::find($setting_voucher->credit));
    };
    $work_code = json_encode(LangDropDownListResource::collection(AccWorkCode::active()->orderBy('code','asc')->get()));
    $sys = AccSystems::get_systems($this->document);
    $document = Document::get_code($sys->value);
    $debt_account = json_encode(AccountSystemsDropDownListResource::collection(AccAccountSystems::get_wherein_id($document->id,$setting_voucher->debit_filter)));
    $credit_account = json_encode(AccountSystemsDropDownListResource::collection(AccAccountSystems::get_wherein_id($document->id,$setting_voucher->credit_filter)));
    $department = json_encode(LangDropDownListResource::collection(AccDepartment::active()->orderBy('code','asc')->get()));
    $bank_account = json_encode(BankAccountDropDownListResource::collection(AccBankAccount::active()->orderBy('bank_account','asc')->get()));
    $case_code = json_encode(LangDropDownListResource::collection(AccCaseCode::active()->orderBy('code','asc')->get()));
    $cost_code = json_encode(LangDropDownListResource::collection(AccCostCode::active()->orderBy('code','asc')->get()));
    $statistical_code = json_encode(LangDropDownListResource::collection(AccStatisticalCode::active()->orderBy('code','asc')->get()));
    $accounted_fast = json_encode(AccountedFastDropDownListResource::collection(AccAccountedFast::active()->orderBy('code','asc')->get()));
    $vat = json_encode(LangTaxDropDownListResource::collection(AccVat::active()->orderBy('code','asc')->get()));
    $subject_code = json_encode(ObjectDropDownListResource::collection(AccObject::active()->orderBy('code','asc')->get()));
    $voucher_list = AccNumberVoucher::all();
    $print = AccPrintTemplate::get_code($this->print);
    return view('acc.receipt_cash_voucher',[ 'key' => $this->key , 'voucher' => $voucher,
                                        'debt_default' => $debt_default,'credit_default' => $credit_default->toArray(),
                                        'bank_account'=>$bank_account,'case_code'=>$case_code,
                                        'cost_code'=>$cost_code,'statistical_code'=>$statistical_code,
                                        'department'=>$department,'debt_account'=>$debt_account,
                                        'credit_account'=>$credit_account,'work_code'=>$work_code ,
                                        'accounted_fast' => $accounted_fast,'voucher_list' => $voucher_list ,
                                        'subject_code' => $subject_code,
                                        'ot' => $ot,
                                        'sg' => $ot,
                                        'vat' => $vat ,'print' => $print]);
  }



  public function save(Request $request){
    $mysql2 = $request->session()->get('mysql2');
    config(['database.connections.mysql2' => $mysql2]);
    $type = 0;
    try{
      $arr = json_decode($request->data);

      if($arr){
         $period = AccPeriod::get_date(Carbon::parse($arr->accounting_date)->format('Y-m'),1);
        if(!$period){
          $general = [];
          $status = 0;
          $removeId = [];
          $removeId_v = [];
          $permission = $request->session()->get('per');
          $user = Auth::user();
          if($permission['e'] == true && $arr->id ){
            $general = AccGeneral::find($arr->id);
            $v = $general->voucher;
            $type = 3;
          }else if($permission['a'] == true && !$arr->id){
            $type = 2;
            $general = new AccGeneral();
            $general->user = $user->id;
            // Lưu số nhảy
            $voucher = AccNumberVoucher::get_menu($this->menu->id);
            // Load Phiếu tự động / Load AutoNumber
              $v = Convert::VoucherMasker($voucher);
              $number = $voucher->number + 1;
              $length_number = $voucher->length_number;
              if(strlen($number."") > $voucher->length_number){
                $voucher->number = 1;
                $voucher->length_number = $length_number + 1;
              }else{
                $voucher->number = $number;
              }
              $voucher->save();
          }

          $general->type = $this->type;
          $general->voucher = $v;
          $general->currency = $arr->currency;
          $general->rate = $arr->rate;
          $general->description = $arr->description;
          $general->voucher_date = $arr->voucher_date;
          $general->accounting_date = $arr->accounting_date;
          $general->traders = $arr->traders;
          $general->subject = $arr->subject_id;
          $general->reference = $arr->reference;
          $general->total_amount = $arr->total_amount;
          $general->total_amount_rate = $arr->total_amount_rate;
          $general->status = 1;
          $general->active = 1;
          $general->save();

          // Tham chiếu / Reference
          // Ktra dòng dư tham chiếu
          if(collect($arr->reference_by)->count()>0){
            $rb = AccGeneral::get_reference_by_whereNotIn($arr->reference_by);
            $rb->each(function ($item, $key) {
              $item->reference_by = 0;
              $item->save();
            });
          // Lưu tham chiếu
            foreach($arr->reference_by as $s => $f){
              $general_reference = AccGeneral::find($f);
              if($general_reference->reference_by == 0){
                $general_reference-> reference_by = $general->id;
                $general_reference->save();
              }
            };
          }else{
              $rb = AccGeneral::get_reference_by($general->id);
              $rb->each(function ($item, $key) {
                $item->reference_by = 0;
                $item->save();
              });
          };

          // CHI TIET / Detail
           foreach($arr->detail as $k => $d){
             $detail = collect([]);
             if($d->id){
               $detail = AccDetail::find($d->id);
             }else{
               $detail = new AccDetail();
             }
             $detail->general_id = $general->id;
             $detail->description = $d->description;
             $detail->debit = $d->debit->id;
             $detail->credit = $d->credit->id;
             $detail->amount = $d->amount;
             $detail->rate = $d->rate;
             $detail->amount_rate = $d->amount * $d->rate;
             $detail->accounted_fast = $d->accounted_fast->id;
             $detail->department = $d->department->id;
             $detail->bank_account = $d->bank_account->id;
             $detail->case_code = $d->case_code->id;
             $detail->cost_code = $d->cost_code->id;
             $detail->statistical_code = $d->statistical_code->id;
             $detail->work_code = $d->work_code->id;
             $detail->lot_number = $d->lot_number;
             $detail->contract = $d->contract;
             $detail->order = $d->order;
             $detail->subject_id_credit = $d->subject_code->id;
             $detail->subject_name_credit = $d->subject_code->name;
             $detail->save();
             array_push($removeId,$detail->id);
             $arr->detail[$k]->id = $detail->id;

             // Lưu số tồn tiền bên Nợ
             if($d->debit->code == '11*'){
               $balance = AccCurrencyCheck::get_type_first($d->debit->id,$arr->currency,null);
               if($balance){
                 $balance->amount = $balance->amount + ($d->amount * $d->rate);
                 $balance->save();
               }else{
                 $balance = new AccCurrencyCheck();
                 $balance->type = $d->debit->id;
                 $balance->currency = $arr->currency;
                 $balance->bank_account = null;
                 $balance->amount = $d->amount * $d->rate;
                 $balance->save();
               }
             }
               // End

               // Lưu số tồn tiền bên Có
               if($d->credit->code == '111*' || $d->credit->code == '113*'){
                 $balance = AccCurrencyCheck::get_type_first($d->credit->id,$arr->currency,null);
                 if($balance){
                   $balance->amount = $balance->amount - ($d->amount * $d->rate);
                   $balance->save();
                 }else{
                   $balance = new AccCurrencyCheck();
                   $balance->type = $d->credit->id;
                   $balance->currency = $arr->currency;
                   $balance->bank_account = null;
                   $balance->amount = 0 - ($d->amount * $d->rate);
                   $balance->save();
                 }
               }else if($d->credit->code == '112*'){
                 $balance = AccCurrencyCheck::get_type_first($d->credit->id,$arr->currency,$d->bank_account);
                 if($balance){
                   $balance->amount = $balance->amount - ($d->amount * $d->rate);
                   $balance->save();
                 }else{
                   $balance = new AccCurrencyCheck();
                   $balance->type = $d->credit->id;
                   $balance->currency = $arr->currency;
                   $balance->bank_account = $d->bank_account;
                   $balance->amount = 0 - ($d->amount * $d->rate);
                   $balance->save();
                 }
               }
               // End
           }

           // Xóa dòng chi tiết
           AccDetail::get_detail_whereNotIn_delete($general->id,$removeId);

           // Lưu VAT
           foreach($arr->tax as $l => $x){
             $tax = collect([]);
             if($x->id){
               $tax = AccVatDetail::find($x->id);
             }else{
               $tax = new AccVatDetail();
             }
             $tax->general_id = $general->id;
             $tax->date_invoice = $x->date_invoice;
             $tax->invoice_form = $x->invoice_form;
             $tax->invoice_symbol = $x->invoice_symbol;
             $tax->invoice = $x->invoice;
             $tax->subject_code = $x->subject_code;
             $tax->subject_name = $x->subject_name;
             $tax->tax_code = $x->tax_code;
             $tax->address = $x->address;
             $tax->description = $x->description;
             $tax->vat_type = $x->vat_type;
             $tax->amount = $x->amount;
             $tax->tax = $x->tax->id;
             $tax->total_amount = $x->total_amount;
             $tax->status = 1;
             $tax->active = 1;
             $tax->save();
             array_push($removeId_v,$tax->id);
             $arr->tax[$l]->id = $tax->id;
           }
           // Xóa dòng chi tiết Vat
           AccVatDetail::get_detail_whereNotIn_delete($general->id,$removeId_v);


           // Lưu file
           if($request->hasFile('files')) {
             $files = $request->file('files');
             foreach($files as $file){
               $com = $request->session()->get('com');
               $filename = $file->getClientOriginalName().'_'.str_random(10);
               $sys = AccSystems::get_systems($this->path);
               $path = public_path().'/'.$sys->value.'/'.$com.'/'. $general->id;
               $pathname = $sys->value . $com.'/'. $general->id.'/'.$filename;
               if(!File::isDirectory($path)){
               File::makeDirectory($path, 0777, true, true);
               }
               $upload_success = $files->move($path, $filename);
               // Lưu lại hình ảnh
               $attach = new AccAttach();
               $attach->general_id = $general->id;
               $attach->name = $filename;
               $attach->path = $pathname;
               $attach->save();
             }
           }

           // Lưu lịch sử
           $h = new AccHistoryAction();
           $h ->create([
           'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
           'user' => Auth::id(),
           'menu' => $this->menu->id,
           'url'  => $this->url,
           'dataz' => \json_encode($arr)]);

           return response()->json(['status'=>true,'message'=> trans('messages.update_success'), 'voucher_name' => $v , 'dataId' => $general->id ,  'data' => $arr ]);
           //
      }else{
          return response()->json(['status'=>false,'message'=> trans('messages.locked_period')]);
      }
      }else{
          return response()->json(['status'=>false,'message'=> trans('messages.update_fail')]);
      }
    }catch(Exception $e){
       // Lưu lỗi
       $err = new Error();
       $err ->create([
         'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
         'user_id' => Auth::id(),
         'menu_id' => $this->menu->id,
         'error' => $e->getMessage(),
         'check' => 0 ]);
       return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
     }
  }


}
