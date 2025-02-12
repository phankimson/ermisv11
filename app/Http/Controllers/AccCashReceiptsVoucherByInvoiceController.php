<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Model\Menu;
use App\Http\Model\AccNumberVoucher;
use App\Http\Model\AccPrintTemplate;
use App\Http\Model\AccObjectType;
use App\Http\Model\AccVatDetail;
use use App\Http\Resources\CashReceiptVoucherInvoiceResource;
use Exception;

class AccCashReceiptsVoucherByInvoiceController extends Controller
{
  protected $url;
  protected $key;
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
     $this->type = 1; // 1 Thu tiền mặt theo hóa đơn
     $this->type_object = 2; // 2 Khách hàng (VD : 2,3 nếu nhiều đối tượng)
     $this->key = "cash-receipts-voucher";
     $this->menu_invoice = Menu::where('code', '=', $this->key."-by-invoice")->first();
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->print = 'PT%';
     $this->document = 'DOCUMENT_TAX';
 }

  public function show(){
    $ot = AccObjectType::get_filter($this->type_object);
    $voucher = AccNumberVoucher::get_menu($this->menu->id); 
    $menu_tab =  Menu::get_menu_like_code($this->key.'%');   
    $voucher_list = AccNumberVoucher::all();
    $print = AccPrintTemplate::get_code($this->print);
    return view('acc.receipt_cash_voucher_by_invoice',[ 'key' => $this->key , 'voucher' => $voucher, 'menu'=>$this->menu_invoice->id,  'menu_tab' => $menu_tab,                                        
                                        'voucher_list' => $voucher_list ,
                                        'ot' => $ot,
                                        'sg' => $ot,
                                        'print' => $print]);
  }

  public function get_data(Request $request){
    $type = 10;
    try{
      $req = json_decode($request->data);
      $data = new CashReceiptVoucherInvoiceResource(AccVatDetail::get_data_load_all($req->object_id,$req->start_date,$req->end_date));
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
