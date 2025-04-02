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
use App\Http\Model\AccCurrencyCheck;
use App\Http\Model\AccPrintTemplate;
use App\Http\Model\Error;
use App\Http\Resources\CashReceiptGeneralResource;
use App\Http\Resources\TypeGeneralResource;
use App\Http\Resources\TypeListGeneralResource;
use App\Http\Model\Imports\AccCashReceiptImport;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\DB;

class AccCashReceiptsGeneralController extends Controller
{
  protected $url;
  protected $key;
  protected $menu;
  protected $group;
  protected $print;
  protected $date_range;
  protected $action;
  public function __construct(Request $request)
 {
     $this->url =  $request->segment(3);
     $this->group = 1; // 1 Thu tiền
     $this->key = "cash-receipts-general";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->print = 'PT%';
     $this->date_range = "DATE_RANGE_GENERAL";
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
    return view('acc.receipt_cash_general',['data' => $data, 'group' =>$group ,'key' => $this->key, 'action' => $action , 'end_date' => $end_date ,'print' => $print, 'start_date'=>$start_date]);
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
             $period = AccPeriod::get_date(Carbon::parse($data->accounting_date)->format('Y-m'),1);
             if(!$period){
               $detail = AccDetail::get_detail_active($data->id,1);

               // Lưu lịch sử
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
               $ty = 1 ;// 1 -> Cash , 2 -> Bank
               $detail->each(function ($d) use ($ty){
                    $d->update(['active'=>0]);
                    // Lưu số tồn
                    $ba = AccCurrencyCheck::get_type_first($ty,$d->currency,null);
                    if($ba){
                      $ba->amount = $ba->amount - $d->amount;
                      $ba->save();
                    }
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
         // Lưu lỗi
         $err = new Error();
         $err ->create([
           'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
           'user_id' => Auth::id(),
           'menu_id' => $this->menu->id,
           'error' => $e->getMessage(),
           'url'  => $this->url,
           'check' => 0 ]);
         return response()->json(['status'=>false,'message'=> trans('messages.unrecored_fail').' '.$e->getMessage()]);
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
             $period = AccPeriod::get_date(Carbon::parse($data->accounting_date)->format('Y-m'),1);
             if(!$period){
               $detail = AccDetail::get_detail_active($data->id,0);
               // Lưu lịch sử
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
               $ty = 1 ;// 1 -> Cash , 2 -> Bank
               $detail->each(function ($d) use ($ty){
                    $d->update(['active'=>1]);
                    // Lưu số tồn
                    $ba = AccCurrencyCheck::get_type_first($ty,$d->currency,null);
                    if($ba){
                      $ba->amount = $ba->amount + $d->amount;
                      $ba->save();
                    }
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
         // Lưu lỗi
         $err = new Error();
         $err ->create([
           'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
           'user_id' => Auth::id(),
           'menu_id' => $this->menu->id,
           'error' => $e->getMessage(),
           'url'  => $this->url,
           'check' => 0 ]);
         return response()->json(['status'=>false,'message'=> trans('messages.recored_fail').' '.$e->getMessage()]);
       }
  }

  public function find(Request $request){
    $type = 10;
    try{
      $req = json_decode($request->data);
      $data = collect(CashReceiptGeneralResource::collection(AccGeneral::get_data_load_between($req->type,$req->start_date_a,$req->end_date_a)));
      if($req->active != ""){
        $data = $data->where('active',$req->active)->values();
      }
      if($data->count()>0){
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

  public function revoucher(Request $request){
    $type = 10;
    try{
      $req = json_decode($request->data);
      // Tìm voucher
      $v = AccNumberVoucher::get_menu($this->menu->id); 
      $date_obj = Convert::dateformatRange($v->format,$req);
      $data = collect(CashReceiptGeneralResource::collection(AccGeneral::get_data_load_between($req->type,$date_obj['start_date'],$date_obj['end_date'])));
      if($data->count()>0){
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

  public function start_voucher(Request $request){
    $type = 10;
    try{
      $req = json_decode($request->data);
      // Tìm voucher
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

  public function change_voucher(Request $request){
    $type = 3;
    try{
      DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
      $req = json_decode($request->data);
      // Tìm voucher & lưu voucher
      $voucher = AccCountVoucher::find($req->voucherId);  
      $voucher->number = $req->number;
      $voucher->save();
      foreach($req->items as $item){
        $general = AccGeneral::find($item->id);
        $general->voucher = $item->revoucher;
        $general->save();
      };   
      DB::connection(env('CONNECTION_DB_ACC'))->commit();
     return response()->json(['status'=>true , 'message'=> trans('messages.update_success')]);
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

    public function DownloadExcel(){
      return Storage::download('public/downloadFile/AccCashReceipts.xlsx');
    }

    
 public function import(Request $request) {
 $type = 5;
  try{
  DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
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
      // Import dữ liệu
      $import = new AccCashReceiptImport;
      Excel::import($import, $file);
      // Lấy lại dữ liệu
      //$array = AccGeneral::with('detail','tax')->get();

      // Import dữ liệu bằng collection
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
      $merged = collect($rs)->push($import->getData());
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
    //broadcast(new \App\Events\DataSendCollection($merged));
    DB::connection(env('CONNECTION_DB_ACC'))->commit();
    return response()->json(['status'=>true,'message'=> trans('messages.success_import'),'data' => $merged]);
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
