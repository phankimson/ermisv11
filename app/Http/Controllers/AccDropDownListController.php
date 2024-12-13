<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\LangDropDownResource;
use App\Http\Resources\DropDownResource;
use App\Http\Model\AccUnit;
use App\Http\Model\AccSuppliesGoodsType;
use App\Http\Model\AccSuppliesGoodsGroup;
use App\Http\Model\AccWarrantyPeriod;
use App\Http\Model\AccStock;
use App\Http\Model\AccVat;
use App\Http\Model\AccExcise;
use App\Http\Model\AccSystems;
use App\Http\Model\Document;
use App\Http\Model\AccSettingAccountGroup;
use App\Http\Model\AccAccountSystems;

class AccDropDownListController extends Controller
{
  protected $document;
  public function __construct(Request $request)
  {
    $this->document = "DOCUMENT_TAX";
  }
  // Đơn vị tính Droplist
  public function unit_dropdown_list(Request $request){
      $default = collect([['value' => '0','text' => "--Select--"]]);
      $data = LangDropDownResource::collection(AccUnit::active()->get());
      $data = $default->merge($data)->values();
      return response()->json($data)->withCallback($request->input('callback'));
  }
  // Loại hàng hóa Droplist
  public function supplies_goods_type_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccSuppliesGoodsType::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }
  // Nhóm hàng hóa Droplist
  public function supplies_goods_group_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccSuppliesGoodsGroup::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }
  // Bảo hành Droplist
  public function warranty_period_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccWarrantyPeriod::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }
   // Kho Droplist
  public function stock_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccStock::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }
  // Thuế VAT Droplist
  public function vat_tax_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccVat::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }
  // Thuế TTDB Droplist
  public function excise_tax_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccExcise::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }
  // Lấy tài khoản nhóm theo mã
  public function setting_account_group_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $sys = AccSystems::get_systems($this->document);
    $doc = Document::get_code($sys->value);
    $code = $request->input('code',null);
    if($code == null){
      $account = AccAccountSystems::active()->get();
    }else{
      $setting = AccSettingAccountGroup::get_code($code);
      $account = collect([]);
      if($setting->account_group){
        $account = AccAccountSystems::get_code_like($doc->id,$setting->account_group);
      }else if($setting->account_filter){
        $account = AccAccountSystems::get_wherein_id($doc->id,$setting->account_filter->pluck('account_systems'));
      }    
    } 
    $data = LangDropDownResource::collection($account);
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

}
