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
use App\Http\Model\AccInventory;
use App\Http\Model\Error;
use App\Http\Resources\InventoryGeneralResource;
use App\Http\Resources\TypeGeneralResource;
use App\Http\Resources\TypeListGeneralResource;
use App\Http\Model\Imports\AccInventoryTransferImport;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\StockCheckTraits;
use App\Http\Traits\FileAttachTraits;

class AccInventoryTransferGeneralController extends Controller
{
  use StockCheckTraits,FileAttachTraits;
  protected $url;
  protected $key;
  protected $menu;
  protected $group;
  protected $print;
  protected $date_range;
  protected $action;
  protected $download;
  protected $key_voucher;
  protected $check_stock;
  public function __construct(Request $request)
 {
     $this->url =  $request->segment(3);
     $this->group = 8; // 8 Nhóm chuyển kho
     $this->key = "inventory-transfer-general";
     $this->key_voucher = "inventory-transfer-voucher";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->print = 'CK%';
     $this->date_range = "DATE_RANGE_GENERAL";
     $this->check_stock = 'CHECK_STOCK';
     $this->download = "AccInventoryTransfer.xlsx";
 }

  public function show(){
    $sys = AccSystems::get_systems($this->date_range);
    $group = TypeListGeneralResource::collection(Menu::get_menu_like_code($this->key_voucher));
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
               $detail->each(function ($d){
                    $d->update(['active'=>0]); 
                    // inventory
                    $inventory = AccInventory::get_detail_first($d->id);
                    if($inventory){
                      // Trả lại số tồn kho
                      $this->increaseStock($d->credit,$inventory->stock_issue,$inventory->item_id,$inventory->quantity); 
                      // Xuất lại số tồn kho
                      $this->reduceStock($d->debit,$inventory->stock_receive,$inventory->item_id,$inventory->quantity);

                      $inventory->update(['active'=>0]);
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
           'error' => $e->getMessage().' - Line '.$e->getLine(),
           'url'  => $this->url,
           'check' => 0 ]);
         return response()->json(['status'=>false,'message'=> trans('messages.unrecored_fail').' '.$e->getMessage().' - Line '.$e->getLine()]);
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
               $detail->each(function ($d){
                    $d->update(['active'=>1]); 
                    // inventory
                    $inventory = AccInventory::get_detail_first($d->id);
                    if($inventory){
                      // Trừ số tồn kho
                      $this->reduceStock($d->credit,$inventory->stock_issue,$inventory->item_id,$inventory->quantity); 
                      // Nhập số tồn kho
                      $this->increaseStock($d->debit,$inventory->stock_receive,$inventory->item_id,$inventory->quantity);

                      $inventory->update(['active'=>1]);
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
           'error' => $e->getMessage().' - Line '.$e->getLine(),
           'url'  => $this->url,
           'check' => 0 ]);
         return response()->json(['status'=>false,'message'=> trans('messages.recored_fail').' '.$e->getMessage().' - Line '.$e->getLine()]);
       }
  }

  public function find(Request $request){
    $type = 10;
    try{
      $req = json_decode($request->data);
      $data = collect(InventoryGeneralResource::collection(AccGeneral::get_data_load_between($req->type,$req->start_date_a,$req->end_date_a)));
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
          'error' => $e->getMessage().' - Line '.$e->getLine(),
          'url'  => $this->url,
          'check' => 0 ]);
        return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage().' - Line '.$e->getLine()]);
      }
  }

  public function revoucher(Request $request){
    $type = 10;
    try{
      $req = json_decode($request->data);
      // Tìm voucher
      $v = AccNumberVoucher::get_menu($this->menu->id); 
      $date_obj = Convert::dateformatRange($v->format,$req);
      $data = collect(InventoryGeneralResource::collection(AccGeneral::get_data_load_between($req->type,$date_obj['start_date'],$date_obj['end_date'])));
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
          'error' => $e->getMessage().' - Line '.$e->getLine(),
          'url'  => $this->url,
          'check' => 0 ]);
        return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage().' - Line '.$e->getLine()]);
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
          'error' => $e->getMessage().' - Line '.$e->getLine(),
          'url'  => $this->url,
          'check' => 0 ]);
        return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage().' - Line '.$e->getLine()]);
      }
  }

  public function change_voucher(Request $request){
    $type = 3;
    try{
      DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
      $req = json_decode($request->data);
      // Tìm voucher & lưu voucher
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
               // Lưu lịch sử
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
                 // inventory
                 $inventory = AccInventory::get_detail_first($d->id);
                 if($inventory){
                   // Trả lại số tồn kho
                   $this->increaseStock($d->credit,$inventory->stock_issue,$inventory->item_id,$inventory->quantity); 
                   // Xuất lại số tồn kho
                   $this->reduceStock($d->debit,$inventory->stock_receive,$inventory->item_id,$inventory->quantity);

                   $inventory->delete();
                 }          
               }             

               // Xóa các dòng chi tiết
               $data->detail()->delete();                                  

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
         // Lưu lỗi
         $err = new Error();
         $err ->create([
           'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
           'user_id' => Auth::id(),
           'menu_id' => $this->menu->id,
           'error' => $e->getMessage().' - Line '.$e->getLine(),
           'url'  => $this->url,
           'check' => 0 ]);
         return response()->json(['status'=>false,'message'=> trans('messages.delete_fail').' '.$e->getMessage().' - Line '.$e->getLine()]);
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
      // Import dữ liệu
      $import = new AccInventoryTransferImport($menu->id,$this->group);
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
       $data = $import->getData();
      // Lấy giá trị kiểm tra kho có âm không
        $ca = AccSystems::get_systems($this->check_stock);
        $acc = "";
      foreach($data['crit'] as $item){
              // Lưu số tồn kho bên Có
                $balance = $this->reduceStock($item['acc_credit'],$item['stock_issue'],$item['item_id'],$item['quantity']); 
              // Lưu số tồn kho bên Nợ
                $this->increaseStock($item['acc_debit'],$item['stock_receipt'],$item['item_id'],$item['quantity']);
              // Kiểm tra kho âm
                  if($ca->value == "1" && $balance->quantity<0){
                    $acc = $item['item_code'];
                    break;
                  }              
               // End
  // Lưu Inventory
         $inventory = new AccInventory(); 
         $inventory->general_id = $item['general_id'];
         $inventory->detail_id = $item['detail_id'];
         $inventory->item_id = $item['item_id'];
         $inventory->item_code = $item['item_code'];
         $inventory->item_name = $item['item_name'];
         $inventory->unit = $item['unit'];
         $inventory->stock_issue = $item['stock_issue'];
         $inventory->stock_receipt= $item['stock_receipt'];
         $inventory->quantity = $item['quantity'];
         $inventory->price = $item['price'];
         $inventory->amount = $item['amount'];
         $inventory->status = 1;
         $inventory->active = 1;
         $inventory->save();
      }
      $merged = collect($rs)->push($data);
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
      if($acc == ""){
        DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
        return response()->json(['status'=>false,'message'=> trans('messages.code_negative',['code'=>$acc])]);
      }else{
        DB::connection(env('CONNECTION_DB_ACC'))->commit();
        return response()->json(['status'=>true,'message'=> trans('messages.success_import'),'data' => $merged]);
      }
    
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
