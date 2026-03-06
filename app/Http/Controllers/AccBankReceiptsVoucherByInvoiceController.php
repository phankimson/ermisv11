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
use App\Http\Model\AccVatDetailPayment;
use App\Http\Model\AccHistoryAction;
use App\Http\Resources\BankVoucherInvoiceResource;
use App\Http\Resources\BankGeneralReadResource;
use Illuminate\Support\Facades\Auth;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\CurrencyCheckTraits;
use App\Http\Traits\FileAttachTraits;
use App\Http\Traits\NumberVoucherTraits;

class AccBankReceiptsVoucherByInvoiceController extends Controller
{
  use CurrencyCheckTraits,FileAttachTraits,NumberVoucherTraits;
  protected $url;
  protected $key;
  protected $key_invoice;
  protected $menu;
  protected $group;
  protected $print;
  protected $type_object;
  protected $document;
  protected $invoice_type;
  protected $path;
  public function __construct(Request $request)
 {
     $this->url =  $request->segment(3);
     $this->group = 3; // 3 NhÃ³m thu ngÃ¢n hÃ ng
     $this->type_object = 2; // 2 KhÃ¡ch hÃ ng (VD : 2,3 náº¿u nhiá»u Ä‘á»‘i tÆ°á»£ng)
     $this->invoice_type = 2; // 1 HÃ³a Ä‘Æ¡n Ä‘áº§u vÃ o , // 2 HÃ³a Ä‘Æ¡n Ä‘áº§u ra
     $this->key = "bank-receipts-voucher";
     $this->key_invoice = "bank-receipts-voucher-by-invoice";     
     $this->menu = Menu::where('code', '=', $this->key_invoice)->first();
     $this->print = 'BCHD%';
     $this->path = 'PATH_UPLOAD_BANK_RECEIPTS';     
 }

  public function show(){
    $ot = AccObjectType::get_filter($this->type_object);
    $voucher = AccNumberVoucher::get_menu($this->menu->id); 
    $menu_tab =  Menu::get_menu_like_code($this->key.'%');   
    $voucher_list = AccNumberVoucher::all();
    $print = AccPrintTemplate::get_code($this->print);
    return view('acc.'.str_replace("-", "_", $this->key_invoice),[ 'key' => $this->key_invoice , 'voucher' => $voucher, 'menu'=>$this->menu->id,  'menu_tab' => $menu_tab,                                        
                                        'voucher_list' => $voucher_list ,
                                        'ot' => $ot,
                                        'sg' => $ot,
                                        'print' => $print]);
  }

  public function get_data(Request $request){
    $type = 10;
    try{
      $req = json_decode($request->data);
      $data = BankVoucherInvoiceResource::collection(AccVatDetail::get_detail_subject($req->subject_id,$req->start_date,$req->end_date,$this->invoice_type,1));
      if($data->count()>0){
        $general = AccGeneral::find($data->first()->general_id);
          if(!$general){
          return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
        }
        $currency = $general->currency;
      }else{
        $currency = 0;
      }
      if($req && $data->count()>0){
        return response()->json(['status'=>true,'data'=> $data,'currency'=>$currency]);
      }else{
        return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
      }    
     }catch(Exception $e){
       return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
     }
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
          $check_payment = false;
          $invoice ='';
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
            // LÆ°u sá»‘ nháº£y
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
          $general->traders = $arr->traders;
          $general->subject = $arr->subject_id;
          $general->total_amount = $arr->total_amount;
          $general->total_amount_rate = $arr->total_amount_rate;
          $general->group = $this->group;
          $general->status = 1;
          $general->active = 1;
          $general->save();          

          $setting_voucher = AccSettingVoucher::get_menu($this->menu->id);
          // CHI TIET / Detail
           foreach($arr->detail as $k => $d){
            $detail = collect([]);
            if($d->detail_id){
              $detail = AccDetail::find($d->detail_id);
              if(!$detail){
                DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
                return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
              }
            }else{
              $detail = new AccDetail();
            }            
             $detail->general_id = $general->id;
             $detail->description = $general->description;
             $detail->currency = $arr->currency;
             $detail->debit = $setting_voucher->debit;  // Láº¥y tá»« seting default
             $detail->credit = $setting_voucher->credit; // Láº¥y tá»« seting default
             $detail->amount = $d->payment;
             $detail->rate = $arr->rate;
             $detail->amount_rate = $d->payment_rate; 
             $detail->bank_account_debit = $arr->bank_account;                
             $detail->subject_id_credit = $arr->subject_id;
             $detail->subject_name_credit = $arr->code." - ".$arr->name;
             $detail->active = 1;
             $detail->status = 1;
             $detail->save();

             // TÃ¬m VAT Ä‘á»ƒ cáº­p nháº­t tráº¡ng thÃ¡i Ä‘Ã£ thanh toÃ¡n (cá»™t payment)
              $vat = AccVatDetail::find($d->vat_detail_id);
             // Ktra xem payment = 1 khÃ´ng. Náº¿u = 1 (Ä‘Ã£ thanh toÃ¡n) thÃ¬ rollback. 
             
             if(($vat->payment == 1 && !$d->id)){              
               $check_payment = true;    
               $invoice =  $vat->invoice;      
               break;      
             }
              $vat_payment = AccVatDetailPayment::sum_vat_detail($vat->id,'payment');
              if((float)$d->payment + $vat_payment - (float)$vat->total_amount >=0){
                $vat->payment = 1;
                $vat->save();
              }

              // LÆ°u VAT payment
               $pm = collect([]);
              if($d->id){
                $pm = AccVatDetailPayment::find($d->id);
                // Ktra xem chá»‰nh sá»­a tt Ä‘á»§ chÆ°a             
                $vat_payment_id = AccVatDetailPayment::sum_vat_detail_not_id($vat->id,'payment',$pm->id);
                if($vat_payment_id+(float)$d->payment <= (float)$vat->total_amount){
                  // Update láº¡i tráº¡ng thÃ¡i thanh toÃ¡n
                  $tax_payment = AccVatDetail::find($pm->vat_detail_id); 
                  if($tax_payment){                   
                  $tax_payment->payment = 0;
                  $tax_payment->save(); 
                  }
                   // Update láº¡i sá»‘ tiá»n Ä‘Ã£ thanh toÃ¡n cá»§a tá»«ng phiáº¿u
                  $tax_payment_update = AccVatDetailPayment::vat_detail_payment_created_at_not_id($pm->vat_detail_id,$pm->created_at,$pm->id);
                  foreach($tax_payment_update as $t){
                    if($t->paid > $pm->paid){
                    $t->paid = ($t->paid - $pm->payment)+$d->payment;
                    $t->remaining = ($t->remaining + $pm->payment)-$d->payment;
                    $t->save();
                    }          
                  }  
                }else{                  
                  $check_payment = true;    
                  $invoice =  $vat->invoice;      
                  break;                 
                }                         
             
              }else{
                $pm = new AccVatDetailPayment();
              }
              $pm->general_id = $general->id;
              $pm->detail_id = $detail->id;
              $pm->vat_detail_id = $d->vat_detail_id;
              $pm->paid = $d->paid;   
              $pm->remaining = $d->remaining;   
              $pm->payment = $d->payment;   
              $pm->rate = $d->rate;  
              $pm->payment_rate = $d->payment_rate;  
              $pm->save();
              // LÆ°u id
              $arr->detail[$k]->id = $pm->id; 

             // LÆ°u sá»‘ tá»“n tiá»n bÃªn Ná»£
             if($setting_voucher->debit){
              $this->increaseCurrency($setting_voucher->debit,$arr->currency,$d->payment,$d->rate,$arr->bank_account);
               //$balance = AccCurrencyCheck::get_type_first($setting_voucher->debit,$arr->currency,null);
               //if($balance){
               //  $balance->amount = $balance->amount + $d->payment;
               //  $balance->save();
               //}else{
               //  $balance = new AccCurrencyCheck();
               //  $balance->type = $setting_voucher->debit;
               //  $balance->currency = $arr->currency;
               //  $balance->bank_account = null;
               //  $balance->amount = $d->payment;
               //  $balance->save();
              // }
             }
               // End
               
           }          
           // LÆ°u file
            $this->saveFile($request,$general->id,$this->path);   

           // LÆ°u lá»‹ch sá»­
           $h = new AccHistoryAction();
           $h ->create([
           'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
           'user' => Auth::id(),
           'menu' => $this->menu->id,
           'url'  => $this->url,
           'dataz' => \json_encode($arr)]);
           if($check_payment == true){
           DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
           return response()->json(['status'=>false,'message'=> trans('messages.invoice_number_paid',['invoice'=>$invoice])]);
           }else if($check_permission == false){
           DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
           return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission')]);
           }else{
           DB::connection(env('CONNECTION_DB_ACC'))->commit();
           return response()->json(['status'=>true,'message'=> trans('messages.'.$action.'_success'), 'voucher_name' => $v , 'dataId' => $general->id ,  'data' => $arr ]);
           //
           }
          
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
      $data = new BankGeneralReadResource(AccGeneral::get_data_load_bank_vat_payment($req));
      if($req && $data->count()>0 ){
        return response()->json(['status'=>true,'data'=> $data]);
      }else{
        return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
      }
     }catch(Exception $e){
       return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
     }
  }


}
