<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\Menu;
use App\Http\Model\AccGeneral;
use App\Http\Model\AccDetail;
use App\Http\Model\AccInventory;
use App\Http\Model\AccPeriod;
use App\Http\Model\AccSystems;
use App\Http\Model\AccNumberVoucher;
use App\Http\Model\AccPrintTemplate;
use App\Http\Model\Error;
use App\Http\Model\AccObjectType;
use App\Http\Resources\InventoryGeneralReadResource;
use App\Http\Model\Imports\AccBankPaymentGeneralImport;
use App\Http\Model\Imports\AccBankPaymentVoucherImport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\NumberVoucherTraits;
use App\Http\Traits\StockCheckTraits;
use App\Http\Traits\FileAttachTraits;

class AccInventoryIssueVoucherController extends Controller
{
  use StockCheckTraits,NumberVoucherTraits,FileAttachTraits;
  protected $url;
  protected $key;
  protected $menu;
  protected $group;
  protected $print;
  protected $type_object;
  protected $document;
  protected $path;
  protected $check_stock;
  protected $download;
  public function __construct(Request $request)
 {
     $this->url =  $request->segment(3);
     $this->group = 6; // 6 Nhóm xuất kho
     $this->type_object = 2; // 1 Nhà cung cấp (VD : 2,3 nếu nhiều đối tượng)
     $this->key = "inventory-issue-voucher";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->print = 'XK%';
     $this->path = 'PATH_UPLOAD_INVENTORY_ISSUE';
     $this->check_stock = 'CHECK_STOCK';
     $this->download = 'AccInventoryIssueVoucher.xlsx';
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
    return view('acc.'.str_replace("-", "_", $this->key),[ 'key' => $this->key , 'voucher' => $voucher, 'menu'=>$this->menu->id, 'menu_tab' => $menu_tab,                                    
                                        'voucher_list' => $voucher_list ,
                                        'ot' => $ot,
                                        'sg' => $ot,
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
          $general->reference = $arr->reference;
          $general->total_quantity = $arr->total_quantity;
          $general->total_amount = $arr->total_amount;
          $general->total_amount_rate = $arr->total_amount * $arr->rate;
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
             // Lấy giá trị kiểm tra kho có âm không
          $ca = AccSystems::get_systems($this->check_stock);
          $acc = "";
          // CHI TIET / Detail
           foreach($arr->detail as $k => $d){
             $detail = collect([]);
             $inventory = collect([]);
            if($d->id){        
               $detail = AccDetail::find($d->id);
               $inventory = AccInventory::get_detail_first($d->id);
                if(!$detail){
                  DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
                  return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
                }
             }else{
               $detail = new AccDetail();
               $inventory = new AccInventory();
             }
             $detail->general_id = $general->id;
             $detail->description = $d->item_code->text;
             $detail->currency = $arr->currency;
             $detail->debit = $d->debit->value;  // Đổi từ id value dạng read
             $detail->credit = $d->credit->value;  // Đổi từ id value dạng read
             $detail->amount = $d->amount;
             $detail->rate = $d->rate;
             $detail->amount_rate = $d->amount * $d->rate;             
             $detail->department = $d->department->value; // Đổi từ id value dạng read
             $detail->case_code = $d->case_code->value;  // Đổi từ id value dạng read
             $detail->cost_code = $d->cost_code->value;  // Đổi từ id value dạng read
             $detail->statistical_code = $d->statistical_code->value;  // Đổi từ id value dạng read
             $detail->work_code = $d->work_code->value;  // Đổi từ id value dạng read
             $detail->lot_number = $d->lot_number;
             $detail->contract = $d->contract;
             $detail->order = $d->order;
             $detail->subject_id_debit = $d->subject_code->value;// Đổi từ id value dạng read
             $detail->subject_name_debit = $d->subject_code->text;// Đổi từ name text dạng read
             $detail->active = 1;
             $detail->status = 1;
             $detail->save();     
       
             array_push($removeId,$detail->id);
             $arr->detail[$k]->id = $detail->id;   
             // Lưu kho
             $item = explode("-", $d->item_code->text);
             $inventory->general_id = $general->id;
             $inventory->detail_id = $detail->id;
             $inventory->item_id = $d->item_code->value;
             $inventory->item_code = isset($item[0]) ? trim($item[0]) : '';
             $inventory->item_name = isset($item[1]) ? trim($item[1]) : '';
             $inventory->unit = $d->unit->value;
             $inventory->stock_issue = $d->stock->value;
             $inventory->quantity = $d->quantity;
             $inventory->price = $d->price;
             $inventory->amount = $d->quantity * $d->price;
             $inventory->active = 1;
             $inventory->status = 1;
             $inventory->save();
       
                                  
               // Lưu số tồn kho bên Có
                $balance = $this->reduceStock($d->credit->value,$d->stock->value,$d->item_code->value,$d->quantity);   
                  if($ca->value == "1" && $balance->quantity<0){
                    $acc = $d->item_code->text;
                    break;
                  }              
               // End
           }           

           // Xóa dòng chi tiết
           AccDetail::get_detail_whereNotIn_delete($general->id,$removeId);
           AccInventory::get_detail_id_whereNotIn_delete($general->id,$removeId);
          
           // Lưu file
           $this->saveFile($request,$general->id,$this->path);    

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
            return response()->json(['status'=>false,'message'=> trans('messages.code_negative',['code'=>$acc])]);
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


  
  public function bind(Request $request){
    $type = 10;
    try{
      $req = json_decode($request->data);
      $data = new InventoryGeneralReadResource(AccGeneral::get_data_load_all($req));
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
          'error' => $e->getMessage().' - Line '.$e->getLine(),
          'url'  => $this->url,
          'check' => 0 ]);
        return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage().' - Line '.$e->getLine()]);
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
        $data = new AccBankPaymentGeneralImport($this->menu);   
        Excel::import($data , $file);
        $detail = new AccBankPaymentVoucherImport($this->menu);   
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
        'error' => $e->getMessage().' - Line '.$e->getLine().' - Line '.$e->getLine(),
        'url'  => $this->url,
        'check' => 0 ]);
      return response()->json(['status'=>false,'message'=> trans('messages.failed_import').' '.$e->getMessage().' - Line '.$e->getLine()]);
    }
  }


}
