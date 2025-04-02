<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Model\Menu;
use App\Http\Model\AccPeriod;
use App\Http\Model\AccNumberVoucher;
use App\Http\Model\AccPrintTemplate;
use App\Http\Model\AccObjectType;
use App\Http\Model\AccVatDetail;
use App\Http\Model\AccGeneral;
use App\Http\Model\AccDetail;
use App\Http\Model\AccSettingVoucher;
use App\Http\Model\AccCurrencyCheck;
use App\Http\Model\AccCountVoucher;
use App\Http\Model\AccVatDetailPayment;
use App\Http\Model\AccSystems;
use App\Http\Model\AccAttach;
use App\Http\Model\AccHistoryAction;
use App\Http\Resources\CashReceiptVoucherInvoiceResource;
use App\Http\Resources\CashReceiptGeneralReadResource;
use App\Http\Model\Error;
use App\Classes\Convert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccCashReceiptsVoucherByInvoiceController extends Controller
{
  protected $url;
  protected $key;
  protected $key_invoice;
  protected $menu_invoice;
  protected $menu;
  protected $type;
  protected $print;
  protected $type_object;
  protected $document;
  protected $path;
  public function __construct(Request $request)
 {
     $this->url =  $request->segment(3);
     $this->type = 2; // 1 Thu tiền mặt, //2 Thu tiền mặt theo hóa đơn
     $this->type_object = 2; // 2 Khách hàng (VD : 2,3 nếu nhiều đối tượng)
     $this->key = "cash-receipts-voucher";
     $this->key_invoice = "cash-receipts-voucher-by-invoice";     
     $this->menu = Menu::where('code', '=', $this->key_invoice)->first();
     $this->print = 'PTHD%';
     $this->document = 'DOCUMENT_TAX';
 }

  public function show(){
    $ot = AccObjectType::get_filter($this->type_object);
    $voucher = AccNumberVoucher::get_menu($this->menu->id); 
    $menu_tab =  Menu::get_menu_like_code($this->key.'%');   
    $voucher_list = AccNumberVoucher::all();
    $print = AccPrintTemplate::get_code($this->print);
    return view('acc.receipt_cash_voucher_by_invoice',[ 'key' => $this->key_invoice , 'voucher' => $voucher, 'menu'=>$this->menu->id,  'menu_tab' => $menu_tab,                                        
                                        'voucher_list' => $voucher_list ,
                                        'ot' => $ot,
                                        'sg' => $ot,
                                        'print' => $print]);
  }

  public function get_data(Request $request){
    $type = 10;
    try{
      $req = json_decode($request->data);
      $data = CashReceiptVoucherInvoiceResource::collection(AccVatDetail::get_detail_subject($req->subject_id,$req->start_date,$req->end_date));
      //dd(AccVatDetail::get_detail_subject($req->subject_id,$req->start_date,$req->end_date));
      $general = AccGeneral::find_subject($req->subject_id);
      if($req && $data->count()>0){
        return response()->json(['status'=>true,'data'=> $data,'currency'=>$general->currency]);
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


  public function save(Request $request){
    $type = 0;
    try{
      DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
      $arr = json_decode($request->data);
      if($arr){
         $period = AccPeriod::get_date(Carbon::parse($arr->accounting_date)->format('Y-m'),1);
        if(!$period){
          $general = [];
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

          $general->type = $this->type;
          $general->voucher = $v;
          $general->currency = $arr->currency;
          $general->rate = $arr->rate;
          $general->description = $arr->description;
          $general->voucher_date = $arr->voucher_date;
          $general->accounting_date = $arr->accounting_date;
          $general->traders = $arr->traders;
          $general->subject = $arr->subject_id;
          $general->total_amount = $arr->total_amount;
          $general->total_amount_rate = $arr->total_amount_rate;
          $general->status = 1;
          $general->active = 1;
          $general->save();      

          $setting_voucher = AccSettingVoucher::get_menu($this->menu->id);
          // CHI TIET / Detail
           foreach($arr->detail as $k => $d){
            $detail = collect([]);
            if($d->id){
              $detail = AccDetail::find($d->id);
            }else{
              $detail = new AccDetail();
            }           
             $detail->general_id = $general->id;
             $detail->description = $general->description;
             $detail->debit = $setting_voucher->debit;  // Lấy từ seting default
             $detail->credit = $setting_voucher->credit; // Lấy từ seting default
             $detail->amount = $d->payment;
             $detail->rate = $arr->rate;
             $detail->amount_rate = $d->payment_rate;             
             $detail->subject_id_credit = $arr->subject_id;
             $detail->subject_name_credit = $arr->code." - ".$arr->name;
             $detail->save();

              // Lưu VAT payment

              $pm = new AccVatDetailPayment();
              $pm->general_id = $general->id;
              $pm->vat_detail_id = $d->vat_detail_id;
              $pm->payment = $d->payment;   
              $pm->rate = $d->rate;  
              $pm->payment_rate = $d->payment_rate;  
              $pm->save();

             // Lưu số tồn tiền bên Nợ
             if($setting_voucher->debit){
               $balance = AccCurrencyCheck::get_type_first($setting_voucher->debit,$arr->currency,null);
               if($balance){
                 $balance->amount = $balance->amount + $d->payment;
                 $balance->save();
               }else{
                 $balance = new AccCurrencyCheck();
                 $balance->type = $setting_voucher->debit;
                 $balance->currency = $arr->currency;
                 $balance->bank_account = null;
                 $balance->amount = $d->payment;
                 $balance->save();
               }
             }
               // End
               
           }          
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
           DB::connection(env('CONNECTION_DB_ACC'))->commit();
           return response()->json(['status'=>true,'message'=> trans('messages.update_success'), 'voucher_name' => $v , 'dataId' => $general->id ,  'data' => $arr ]);
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
      $data = new CashReceiptGeneralReadResource(AccGeneral::get_data_load_vat_payment($req));
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


}
