<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\Menu;
use App\Http\Model\AccGeneral;
use App\Http\Model\AccDetail;
use App\Http\Model\AccAttach;
use App\Http\Model\AccPeriod;
use App\Http\Model\AccSystems;
use App\Http\Model\AccCurrencyCheck;
use App\Http\Model\AccNumberVoucher;
use App\Http\Model\AccCountVoucher;
use App\Http\Model\AccPrintTemplate;
use App\Http\Model\Error;
use App\Classes\Convert;
use App\Http\Resources\BankTransferGeneralReadResource;
use App\Http\Model\Imports\AccBankTransferGeneralImport;
use App\Http\Model\Imports\AccBankTransferVoucherImport;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AccBankTransferVoucherController extends Controller
{
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
     $this->group = 0; // Nhóm chuyển nội bộ
     $this->key = "bank-transfer-voucher";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->print = 'BT%';
     $this->path = 'PATH_UPLOAD_BANK_TRANSFER';
     $this->document = 'DOCUMENT_TAX';
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
                $v = Convert::VoucherMasker1($voucher,$prefix);
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

              // Lấy giá trị kiểm tra tiền mặt có âm không
          $ca = AccSystems::get_systems($this->check_cash);
          $acc = "";

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
             $detail->bank_account_debit = $d->bank_account_debit->value;  // Đổi từ id value dạng read   
             $detail->bank_account_credit = $d->bank_account_credit->value;  // Đổi từ id value dạng read             
             $detail->active = 1;
             $detail->status = 1;
             $detail->save();
       
             array_push($removeId,$detail->id);
             $arr->detail[$k]->id = $detail->id;      
             // Lưu số tồn tiền bên Nợ
             if(substr($d->debit->text,0,3) === '112'){     
               $balance = AccCurrencyCheck::get_type_first($d->debit->value,$arr->currency,$d->bank_account_debit->value);     
               if($balance){
                 $balance->amount = $balance->amount + ($d->amount * $d->rate);
                 $balance->save();
               }else{
                 $balance = new AccCurrencyCheck();
                 $balance->type = $d->debit->value;
                 $balance->currency = $arr->currency;
                 $balance->bank_account = $d->bank_account_debit->value;
                 $balance->amount = $d->amount * $d->rate;
                 $balance->save();
               }
             }
               // End
           
               // Lưu số tồn tiền bên Có
              if(substr($d->credit->text,0,3) == '112'){
                 $balance = AccCurrencyCheck::get_type_first($d->credit->value,$arr->currency,$d->bank_account_credit->value);     
                 if($balance){
                   $balance->amount = $balance->amount - ($d->amount * $d->rate);
                   $balance->save();
                 }else{                  
                   $balance = new AccCurrencyCheck();
                   $balance->type = $d->credit->value;
                   $balance->currency = $arr->currency;
                   $balance->bank_account = $d->bank_account_credit->value;
                   $balance->amount = 0 - ($d->amount * $d->rate);
                   $balance->save();
                 }
                   if($ca->value == "1" && $balance->amount<0){
                    $acc = $d->credit->text;
                    break;
                  }
               }
               // End
           }

           // Xóa dòng chi tiết
           AccDetail::get_detail_whereNotIn_delete($general->id,$removeId);
           
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
           if($acc != ""){
            DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
            return response()->json(['status'=>false,'message'=> trans('messages.account_negative',['account'=>$acc])]);
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
      $data = new BankTransferGeneralReadResource(AccGeneral::get_data_load_all($req));
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
        // Đổi dữ liệu Excel sang collect
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
