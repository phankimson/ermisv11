<?php

namespace App\Http\Controllers;

use App\Classes\Convert;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\Menu;
use App\Http\Model\AccGeneral;
use App\Http\Model\AccDetail;
use App\Http\Model\AccSystems;
use App\Http\Model\AccPeriod;
use App\Http\Model\AccNumberVoucher;
use App\Http\Model\AccCountVoucher;
use App\Http\Model\AccPrintTemplate;
use App\Http\Resources\BankGeneralResource;
use App\Http\Resources\TypeGeneralResource;
use App\Http\Resources\TypeListGeneralResource;
use App\Http\Model\Imports\AccBankReceiptImport;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\CurrencyCheckTraits;
use App\Http\Traits\FileAttachTraits;
use App\Http\Traits\VatDetailPaymentTraits;
use App\Http\Traits\BankCompareTraits;

class AccBankReceiptsGeneralController extends Controller
{
  use CurrencyCheckTraits,FileAttachTraits,VatDetailPaymentTraits,BankCompareTraits;
  protected $url;
  protected $key;
  protected $key_voucher;
  protected $menu;
  protected $group;
  protected $print;
  protected $date_range;
  protected $action;
  protected $download;
  public function __construct(Request $request)
 {
     $this->url =  $request->segment(3);
     $this->group = 3; // 1 Nhom thu ngan hang
     $this->key = "bank-receipts-general";
     $this->key_voucher = "bank-receipts-voucher";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->print = 'BC%';
     $this->date_range = "DATE_RANGE_GENERAL";
     $this->download = 'AccBankReceipts.xlsx';
 }

  public function show(){
    $sys = AccSystems::get_systems($this->date_range);
    $group =  TypeListGeneralResource::collection(Menu::get_menu_by_group($this->menu->type,$this->group));
    $action = new TypeGeneralResource($group->first());
    $end_date_default = Carbon::now();
    $start_date_default = Carbon::now()->subDays($sys->value);
    $data = AccGeneral::get_range_date(null,$action->type,$end_date_default,$start_date_default);
    $end_date = $end_date_default->format('d/m/Y');
    $start_date = $start_date_default->format('d/m/Y');
    $print = AccPrintTemplate::get_code($this->print);
    return view('acc.'.str_replace("-", "_", $this->key),['data' => $data, 'group' =>$group ,'key' => $this->key, 'action' => $action , 'end_date' => $end_date ,'print' => $print, 'start_date'=>$start_date]);
  }

  public function unwrite(Request $request) {
    $type = 3;
       try{
        DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
         $permission = $request->session()->get('per');
         $arr = json_decode($request->data);
         if($arr){
           if($permission['e'] == true){
             $data = AccGeneral::find($arr);
              if(!$data){
               return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
             }
             $period = AccPeriod::get_date(Carbon::parse($data->accounting_date)->format('Y-m'),1);
             if(!$period){
               $detail = AccDetail::get_detail_active($data->id,1);

               // Luu lich su
               $h = new AccHistoryAction();
               $h ->create([
               'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
               'user' => Auth::id(),
               'menu' => $this->menu->id,
               'url'  => $this->url,
               'dataz' => \json_encode($data)]);
               $data->active = 0;
               $data->save();
               //DETAIL
               $detail->each(function ($d){
                    $d->update(['active'=>0]);
                    // Luu so lai so ton ben No
                    if(substr($d->debit()->first()->code,0,3) == "112"){
                      $this->reduceCurrencyEdit($d->debit,$d->currency,$d->amount,$d->bank_account_debit);
                      //  $ba = AccCurrencyCheck::get_type_first($d->debit,$d->currency,null);
                      //if($ba){
                      //  $ba->amount = $ba->amount - $d->amount;
                      //  $ba->save();
                      //}
                    }
                    // Luu so lai so ton ben Co
                    if(substr($d->credit()->first()->code,0,3) == ("111"||"113")){
                      $this->increaseCurrencyEdit($d->credit,$d->currency,$d->amount);
                      //  $ca = AccCurrencyCheck::get_type_first($d->credit,$d->currency,null);
                      //if($ca){
                      //  $ca->amount = $ca->amount + $d->amount;
                      //  $ca->save();
                      //}
                    }
                   // else if(substr($d->credit()->first()->code,0,3) == "112"){
                   //    $ca = AccCurrencyCheck::get_type_first($d->credit,$d->currency,$d->bank_account);
                   //   if($ca){
                   //    $ca->amount = $ca->amount + $d->amount;
                   //   $ca->save();
                   //   }
                   // }
                  
                });
                DB::connection(env('CONNECTION_DB_ACC'))->commit();
               return response()->json(['status'=>true,'message'=> trans('messages.unrecored_success')]);
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
        return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__, 'messages.unrecored_fail');
       }
  }

  public function write(Request $request) {
    $type = 3;
       try{
        DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
         $permission = $request->session()->get('per');
         $arr = json_decode($request->data);
         if($arr){
           if($permission['e'] == true){
             $data = AccGeneral::find($arr);
              if(!$data){
               return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
             }
             $period = AccPeriod::get_date(Carbon::parse($data->accounting_date)->format('Y-m'),1);
             if(!$period){
               $detail = AccDetail::get_detail_active($data->id,0);
               // Luu lich su
               $h = new AccHistoryAction();
               $h ->create([
               'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
               'user' => Auth::id(),
               'menu' => $this->menu->id,
               'url'  => $this->url,
               'dataz' => \json_encode($data)]);
               $data->active = 1;
               $data->save();

               //DETAIL
               $detail->each(function ($d){
                    $d->update(['active'=>1]);
                    // Luu so lai so ton ben No
                  if(substr($d->debit()->first()->code,0,3) == "112"){
                    $this->increaseCurrencyEdit($d->debit,$d->currency,$d->amount,$d->bank_account_debit);
                    //$ba = AccCurrencyCheck::get_type_first($d->debit,$d->currency,null);
                    //if($ba){
                    //  $ba->amount = $ba->amount + $d->amount;
                    //  $ba->save();
                    //}
                  }
                   // Luu so lai so ton ben Co
                    if(substr($d->credit()->first()->code,0,3) == ("111"||"113")){
                       $this->reduceCurrencyEdit($d->credit,$d->currency,$d->amount);
                      // $ca = AccCurrencyCheck::get_type_first($d->credit,$d->currency,null);
                      //if($ca){
                      //  $ca->amount = $ca->amount - $d->amount;
                      //  $ca->save();
                      //}
                     }
                    //else if(substr($d->credit()->first()->code,0,3) == "112"){
                    //   $ca = AccCurrencyCheck::get_type_first($d->credit,$d->currency,$d->bank_account);
                    //  if($ca){
                    //    $ca->amount = $ca->amount - $d->amount;
                    //    $ca->save();
                    //  }
                    //}

                });
                DB::connection(env('CONNECTION_DB_ACC'))->commit();
               return response()->json(['status'=>true,'message'=> trans('messages.unrecored_success')]);
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
        return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__, 'messages.recored_fail');
       }
  }

  public function find(Request $request){
    $type = 10;
    try{
      $req = json_decode($request->data);
      $data = collect(BankGeneralResource::collection(AccGeneral::get_data_load_between($req->type,$req->start_date_a,$req->end_date_a)));
      if($req->active != ""){
        $data = $data->where('active',$req->active)->values();
      }
      if($data->count()>0){
        return response()->json(['status'=>true,'data'=> $data]);
      }else{
        return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
      }
     }catch(Exception $e){
       return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
     }
  }

  public function revoucher(Request $request){
    $type = 10;
    try{
      $req = json_decode($request->data);
      // Tim voucher
      $v = AccNumberVoucher::get_menu($this->menu->id); 
      $date_obj = Convert::dateformatRange($v->format,$req);
      $data = collect(BankGeneralResource::collection(AccGeneral::get_data_load_between($req->type,$date_obj['start_date'],$date_obj['end_date'])));
      if($data->count()>0){
        return response()->json(['status'=>true,'data'=> $data]);
      }else{
        return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
      }
     }catch(Exception $e){
       return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
     }
  }

  public function start_voucher(Request $request){
    $type = 10;
    try{
      $req = json_decode($request->data);
      // Tim voucher
      $v = AccNumberVoucher::get_menu($req->type); 
      $val = Convert::dateformatArr($v->format,$req->year.'-'.$req->month.'-'.$req->day);
      $voucher = AccCountVoucher::get_count_voucher($v->id,$v->format,$val['day_format'],$val['month_format'],$val['year_format']);  
      $data = collect($voucher);
      $data->put('change_voucher',$v->change_voucher);
      $data->put('prefix', $v->prefix);
      if($voucher->count()>0){
        return response()->json(['status'=>true,'data'=> $data]);
      }else{
        return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
      }
     }catch(Exception $e){
       return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
     }
  }

  public function change_voucher(Request $request){
    $type = 3;
    try{
      DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
      $req = json_decode($request->data);
      // Tim voucher & luu voucher
      $voucher = AccCountVoucher::find($req->voucherId);  
      if($voucher){
      $voucher->number = $req->number;
      $voucher->save();
      }
      foreach($req->items as $item){
        $general = AccGeneral::find($item->id);
        if($general){
          $general->voucher = $item->revoucher;
          $general->save();
        }        
      };   
      DB::connection(env('CONNECTION_DB_ACC'))->commit();
     return response()->json(['status'=>true , 'message'=> trans('messages.update_success')]);
     }catch(Exception $e){
      DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
      return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
     }
  }

  

  public function delete(Request $request) {
    $type = 4;
       try{
        DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
         $permission = $request->session()->get('per');
         $arr = json_decode($request->data);
         if($arr){
           $data = AccGeneral::get_id_with_detail($arr,['detail','tax','attach','vat_detail_payment']);           
           $period = AccPeriod::get_date(Carbon::parse($data->accounting_date)->format('Y-m'),1);
           if(!$period){
             if($permission['d'] == true){             

               // Luu lich su
               $h = new AccHistoryAction();
               $h ->create([
               'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
               'user' => Auth::id(),
               'menu' => $this->menu->id,
               'url'  => $this->url,
               'dataz' => \json_encode($data)]);
               //           
               
               $detail = $data->detail;
               
               foreach($detail as $d){
                //Clear so tien ben No
                 $this->reduceCurrencyEdit($d->debit,$d->currency,$d->amount,$d->bank_account_debit);

                //Clear so tien ben Co
                $this->increaseCurrencyEdit($d->credit,$d->currency,$d->amount);
         
               }             

               // Xoa cac dong chi tiet
               $data->detail()->delete();              

                // Update lai trang thai thanh toan
              $tax_payment = $data->vat_detail_payment;
              $this->updateStatusPayment($tax_payment);
               
                 // Update lai trang thai so sanh ngan hang
               $this->updateActiveCompare($data->compare_id); 
              
                // Xoa cac dong thue
               $data->tax()->delete();

                // Xoa cac dong thanh toan
                $data->vat_detail_payment()->delete();                         

                $attach = $data->attach;
                 if($attach->count()>0){
                  $this->deleteFile($attach);  
                }     

               $data->delete(); 
               DB::connection(env('CONNECTION_DB_ACC'))->commit();
               return response()->json(['status'=>true,'message'=> trans('messages.delete_success')]);
           }else{
             return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission_delete')]);
           }
         }else{
           return response()->json(['status'=>false,'message'=> trans('messages.locked_period')]);
         }
        }else{
          return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
        }
       }catch(Exception $e){
        DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
        return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__, 'messages.delete_fail');
       }
  }

    public function DownloadExcel(){
      return Storage::download('public/downloadFile/'.$this->download);
    }

    
 public function import(Request $request) {
 $type = 5;
  try{
  DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
  $permission = $request->session()->get('per');
  if($permission['a'] && $request->hasFile('file')){
      if($request->file->getClientOriginalName() == $this->download){
    //Check
    $request->validate([
        'file' => 'required|mimeTypes:'.
              'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,'.
              'application/vnd.ms-excel',
    ]);
      $rs = json_decode($request->data);
      $menu = Menu::where('code', '=', $this->key_voucher)->first();
      $file = $request->file;
      // Import du lieu
      $import = new AccBankReceiptImport($menu->id,$this->group);
      Excel::import($import, $file);
      // Lay lai du lieu
      //$array = AccGeneral::with('detail','tax')->get();

      // Import du lieu bang collection
      //$results = Excel::toCollection(new HistoryActionImport, $file);
      //dump($results);
      //foreach($results[0] as $item){
      //  $data = new HistoryAction();
      //  $data->type = $item->get('type');
      //  $data->user = $item->get('user');
      //  $data->menu = $item->get('menu');
      //  $data->dataz = $item->get('dataz');
      //  $data->save();
      //  $arr->push($data);
      //}
      $data = $import->getData();
      foreach($data['crit'] as $item){
      // Luu so ton ben No
      if(substr($item['debit'],0,3) === '112'){   
         $this->increaseCurrency($item['debit_id'],$item['currency'],$item['amount'],$item['rate'],$item['bank_account']);     
         //$balance = AccCurrencyCheck::get_type_first($item['debit_id'],$item['currency'],$item['bank_account']);
        //if($balance){
        //      $balance->amount = $balance->amount + ($item['amount'] * $item['rate']);
        //      $balance->save();
        //    }else{
        //      $balance = new AccCurrencyCheck();
        //      $balance->type = $item['debit_id'];
        //      $balance->currency = $item['currency'];
        //      $balance->bank_account = $item['bank_account'];
        //      $balance->amount = $item['amount'] * $item['rate'];
        //      $balance->save();
        //    }
      }
      // Luu so ton ben Co
      if(substr($item['credit'],0,3) === ('111' ||  '113')){   
          $this->reduceCurrency($item['credit_id'],$item['currency'],$item['amount'],$item['rate']);              
             // $balance = AccCurrencyCheck::get_type_first($item['credit_id'],$item['currency'],null);
             // if($balance){
             //   $balance->amount = $balance->amount - ($item['amount'] * $item['rate']);
             //   $balance->save();
             // }else{
             //   $balance = new AccCurrencyCheck();
             //   $balance->type = $item['credit_id'];
             //   $balance->currency = $item['currency'];
             //   $balance->bank_account = null;
             //   $balance->amount = 0 - ($item['amount'] * $item['rate']);
             //   $balance->save();
             // }                  
            }
      }    

      $merged = collect($rs)->push($data);
      //dump($merged);
    // Luu lich su
    $h = new AccHistoryAction();
    $h ->create([
      'type' => $type, // Add : 2 , Edit : 3 , Delete : 4, Import : 5
      'user' => Auth::id(),
      'menu' => $this->menu->id,
      'url'  => $this->url,
      'dataz' => \json_encode($merged)]);
    //
    //Storage::delete($savePath.$filename);
    //broadcast(new \App\Events\DataSendCollection($merged));
    DB::connection(env('CONNECTION_DB_ACC'))->commit();
    return response()->json(['status'=>true,'message'=> trans('messages.success_import'),'data' => $merged]);
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
