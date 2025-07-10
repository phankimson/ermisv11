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
use App\Http\Model\AccNumberVoucher;
use App\Http\Model\AccCountVoucher;
use App\Http\Model\AccPrintTemplate;
use App\Http\Model\Error;
use App\Classes\Convert;
use App\Http\Model\AccObject;
use App\Http\Model\AccObjectType;
use App\Http\Resources\CashReceiptGeneralReadResource;
use App\Http\Model\Imports\AccCashReceiptGeneralImport;
use App\Http\Model\Imports\AccCashReceiptVoucherImport;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AccCashReceiptsVoucherController extends Controller
{
  protected $url;
  protected $key;
  protected $menu;
  protected $group;
  protected $print;
  protected $type_object;
  protected $document;
  protected $path;
  protected $invoice_type;
  public function __construct(Request $request)
 {
     $this->url =  $request->segment(3);
     $this->invoice_type = 2; // 1 Hóa đơn đầu vào , // 2 Hóa đơn đầu ra
     $this->group = 1; // 1 Nhóm thu tiền mặt
     $this->type_object = 2; // 2 Khách hàng (VD : 2,3 nếu nhiều đối tượng)
     $this->key = "cash-receipts-voucher";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->print = 'PT%';
     $this->path = 'PATH_UPLOAD_CASH_RECEIPTS';
     $this->document = 'DOCUMENT_TAX';
 }

  public function show(){
    $ot = AccObjectType::get_filter($this->type_object);
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
    return view('acc.receipt_cash_voucher',[ 'key' => $this->key , 'voucher' => $voucher, 'menu'=>$this->menu->id, 'menu_tab' => $menu_tab,                                    
                                        'voucher_list' => $voucher_list ,
                                        'ot' => $ot,
                                        'sg' => $ot,
                                        'print' => $print]);
  }



  public function save(Request $request){
    $type = 0;
    try{
      DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
      $arr = json_decode($request->data);
      if($arr){
         $period = AccPeriod::get_date(Carbon::parse($arr->accounting_date)->format('Y-m'),1);
        if(!$period){
          dd($arr);
          $general = [];
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
            // Thay đổi số nhảy theo yêu cầu DD MM YY
            $voucher_id = $voucher->id;
            $voucher_length_number = $voucher->length_number;
            $format = $voucher->format;
            $prefix = $voucher->prefix;
            if($voucher->change_voucher == 1){
              $val = Convert::dateformatArr($format,$arr->accounting_date);
              $voucher = AccCountVoucher::get_count_voucher($voucher_id,$format,$val['day_format'],$val['month_format'],$val['year_format']);              
              if(!$voucher){
                $voucher = new AccCountVoucher();
                $voucher->number_voucher = $voucher_id;
                $voucher->format = $format;
                $voucher->day = $val['day_format'];
                $voucher->month = $val['month_format'];
                $voucher->year = $val['year_format'];
                $voucher->length_number = $voucher_length_number;
                $voucher->active = 1;
              }
            }                
                // Load Phiếu tự động / Load AutoNumber
                $v = Convert::VoucherMasker1($voucher,$prefix);
                if($voucher->number == 0 ||  !$voucher->number ){
                  $number = 1;
                }else{
                  $number = $voucher->number + 1;
                }  
                $length_number = $voucher->length_number;
                if(strlen($number."") > $voucher->length_number){
                  $voucher->length_number = $length_number + 1;
                }
                  $voucher->number = $number;
                
                $voucher->save();
          }
          $general->type = $this->menu->id;
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
          $general->group = $this->group;
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
             $detail->currency = $arr->currency;
             $detail->debit = $d->debit->value;  // Đổi từ id value dạng read
             $detail->credit = $d->credit->value;  // Đổi từ id value dạng read
             $detail->amount = $d->amount;
             $detail->rate = $d->rate;
             $detail->amount_rate = $d->amount * $d->rate;
             $detail->accounted_fast = $d->accounted_fast->value;  // Đổi từ id value dạng read
             $detail->department = $d->department->value; // Đổi từ id value dạng read
             $detail->bank_account = $d->bank_account->value;  // Đổi từ id value dạng read
             $detail->case_code = $d->case_code->value;  // Đổi từ id value dạng read
             $detail->cost_code = $d->cost_code->value;  // Đổi từ id value dạng read
             $detail->statistical_code = $d->statistical_code->value;  // Đổi từ id value dạng read
             $detail->work_code = $d->work_code->value;  // Đổi từ id value dạng read
             $detail->lot_number = $d->lot_number;
             $detail->contract = $d->contract;
             $detail->order = $d->order;
             $detail->subject_id_credit = $d->subject_code->value;// Đổi từ id value dạng read
             $detail->subject_name_credit = $d->subject_code->text;// Đổi từ name text dạng read
             $detail->save();
       
             array_push($removeId,$detail->id);
             $arr->detail[$k]->id = $detail->id;       
          
             // Lưu số tồn tiền bên Nợ
             if(substr($d->debit->text,0,3) === '111'){     
               $balance = AccCurrencyCheck::get_type_first($d->debit->value,$arr->currency,null);
               if($balance){
                 $balance->amount = $balance->amount + ($d->amount * $d->rate);
                 $balance->save();
               }else{
                 $balance = new AccCurrencyCheck();
                 $balance->type = $d->debit->value;
                 $balance->currency = $arr->currency;
                 $balance->bank_account = null;
                 $balance->amount = $d->amount * $d->rate;
                 $balance->save();
               }
             }
               // End

               // Lưu số tồn tiền bên Có
               if(substr($d->credit->text,0,3) === ('111' ||  '113')){    
                 $balance = AccCurrencyCheck::get_type_first($d->credit->value,$arr->currency,null);
                 if($balance){
                   $balance->amount = $balance->amount - ($d->amount * $d->rate);
                   $balance->save();
                 }else{
                   $balance = new AccCurrencyCheck();
                   $balance->type = $d->credit->value;
                   $balance->currency = $arr->currency;
                   $balance->bank_account = null;
                   $balance->amount = 0 - ($d->amount * $d->rate);
                   $balance->save();
                 }
               }else if(substr($d->credit->text,3) == '112'){
                 $balance = AccCurrencyCheck::get_type_first($d->credit->id,$arr->currency,$d->bank_account);
                 if($balance){
                   $balance->amount = $balance->amount - ($d->amount * $d->rate);
                   $balance->save();
                 }else{
                   $balance = new AccCurrencyCheck();
                   $balance->type = $d->credit->value;
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
           $check_invoice = false;
           $invoice = '';
           // Lưu VAT
           foreach($arr->tax as $l => $x){
             $tax = collect([]);
                // Kiểm tra có trùng MST, số hóa đơn 
                $arr_check = array(
                  ['invoice', '=',$x->invoice],
                  ['invoice_symbol', '=',$x->invoice_symbol],
                  //['invoice_form', '=',$x->invoice_form],
                  ['tax_code', '=',$x->tax_code],
                  ['id','<>',$x->id]
                );
                $tax_check = AccVatDetail::get_invoice($arr_check);
                if($tax_check){
                  $check_invoice = true;
                    $invoice = $x->invoice;
                    break;
                }
                // End
                // Update mẫu, ký tự hóa đơn
                $obj = AccObject::find($x->subject_id);
                if($obj){
                  $obj->invoice_form = $x->invoice_form;
                  $obj->invoice_symbol = $x->invoice_symbol;
                  $obj->save();
                }
                // End
             if($x->id){
               $tax = AccVatDetail::find($x->id);              
             }else{
               $tax = new AccVatDetail();
             }
             $total_amount = $x->amount+$x->tax;
             $tax->general_id = $general->id;
             $tax->date_invoice = $x->date_invoice;
             $tax->invoice_type = $this->invoice_type;
             $tax->invoice_form = $x->invoice_form;
             $tax->invoice_symbol = $x->invoice_symbol;
             $tax->invoice = $x->invoice;
             $tax->subject_id = $x->subject_id;
             $tax->subject_code = $x->subject_code;
             $tax->subject_name = $x->subject_name;
             $tax->tax_code = $x->tax_code;
             $tax->address = $x->address;
             $tax->description = $x->description;
             $tax->vat_type = $x->vat_type->value;// Đổi từ id value dạng read
             $tax->amount = $x->amount;
             $tax->tax = $x->tax;
             $tax->total_amount = $total_amount;
             $tax->rate = $x->rate;
             $tax->total_amount_rate = $total_amount*$x->rate;
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
               $filename = $file->getClientOriginalName().'_'.Str::random(10);
               $sys = AccSystems::get_systems($this->path);
               $path = public_path().'/'.$sys->value.'/'.$com.'/'. $general->id;
               $pathname = $sys->value . $com.'/'. $general->id.'/'.$filename;
               if(!File::isDirectory($path)){
               File::makeDirectory($path, 0777, true, true);
               }
               $files->move($path, $filename);
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
          if($check_invoice == true){
           DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
           return response()->json(['status'=>false,'message'=> trans('messages.invoice_number_duplicate',['invoice'=>$invoice])]);
           }else{
           DB::connection(env('CONNECTION_DB_ACC'))->commit();
           return response()->json(['status'=>true,'message'=> trans('messages.update_success'), 'voucher_name' => $v , 'dataId' => $general->id ,  'data' => $arr ]);
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


  
  public function bind(Request $request){
    $type = 10;
    try{
      $req = json_decode($request->data);
      $data = new CashReceiptGeneralReadResource(AccGeneral::get_data_load_all($req));
      if($req && $data->count()>0 ){
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

  public function DownloadExcel(){
    return Storage::download('public/downloadFile/AccCashReceiptsVoucher.xlsx');
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
        //$rs = json_decode($request->data);
  
        $file = $request->file;
        // Đổi dữ liệu Excel sang collect
        config(['excel.imports.read_only' => false]);
        $data = new AccCashReceiptGeneralImport($this->menu);
        Excel::import($data , $file);
        $detail = new AccCashReceiptVoucherImport;
        Excel::import($detail, $file); 
        $merged = collect($data->getData())->push($detail->getData());            
        return response()->json(['status'=>true,'message'=> trans('messages.success_import'),'data'=>$merged]);
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
