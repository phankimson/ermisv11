<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Model\Menu;
use App\Http\Model\AccNumberVoucher;
use App\Http\Model\AccPrintTemplate;
use App\Http\Model\AccObjectType;

class AccCashReceiptsVoucherByInvoiceController extends Controller
{
  protected $url;
  protected $key;
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
     $this->menu = Menu::where('code', '=', $this->key."-by-invoice")->first();
     $this->print = 'PT%';
     $this->document = 'DOCUMENT_TAX';
 }

  public function show(){
    $ot = AccObjectType::get_filter($this->type_object);
    $voucher = AccNumberVoucher::get_menu($this->menu->id); 
    $menu_tab =  Menu::get_menu_like_code($this->key.'%');   
    $voucher_list = AccNumberVoucher::all();
    $print = AccPrintTemplate::get_code($this->print);
    return view('acc.receipt_cash_voucher_by_invoice',[ 'key' => $this->key , 'voucher' => $voucher, 'menu'=>$this->menu->id,  'menu_tab' => $menu_tab,                                        
                                        'voucher_list' => $voucher_list ,
                                        'ot' => $ot,
                                        'sg' => $ot,
                                        'print' => $print]);
  }



}
