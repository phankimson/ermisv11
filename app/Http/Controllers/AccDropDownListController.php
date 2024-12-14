<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\LangDropDownResource;
use App\Http\Resources\BankDropDownResource;
use App\Http\Resources\AccountedFastDropDownResource;
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
use App\Http\Model\AccCaseCode;
use App\Http\Model\AccCostCode;
use App\Http\Model\AccStatisticalCode;
use App\Http\Model\AccWorkCode;
use App\Http\Model\AccBankAccount;
use App\Http\Model\AccDepartment;
use App\Http\Model\AccObject;
use App\Http\Model\AccAccountedFast;

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
      $data = LangDropDownResource::collection(AccUnit::active()->orderBy('code','asc')->get());
      $data = $default->merge($data)->values();
      return response()->json($data)->withCallback($request->input('callback'));
  }
  // Loại hàng hóa Droplist
  public function supplies_goods_type_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccSuppliesGoodsType::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }
  // Nhóm hàng hóa Droplist
  public function supplies_goods_group_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccSuppliesGoodsGroup::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }
  // Bảo hành Droplist
  public function warranty_period_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccWarrantyPeriod::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }
   // Kho Droplist
  public function stock_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccStock::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }
  // Thuế VAT Droplist
  public function vat_tax_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccVat::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }
  // Thuế TTDB Droplist
  public function excise_tax_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccExcise::active()->orderBy('code','asc')->get());
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
   // Mã vụ việc
   public function case_code_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccCaseCode::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

   // Mã chi phí
   public function cost_code_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccCostCode::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

   // Mã thống kê
   public function statistical_code_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccStatisticalCode::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // Mã công việc
  public function work_code_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccWorkCode::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // TK Ngân hàng
  public function bank_account_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = BankDropDownResource::collection(AccBankAccount::active()->orderBy('bank_account','asc')->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

   // Đối tượng
   public function object_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccObject::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

   // Bộ phận
   public function department_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccDepartment::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // Tài khoản
  public function account_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccAccountSystems::active()->orderBy('code','asc')->doesntHave('account')->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // Hạch toán nhanh
  public function accounted_fast_dropdown_list(Request $request){    
    $val = $request->input('value',null);
    if($val){
      $arr = AccAccountedFast::findOrFail($val);   
      $data = new AccountedFastDropDownResource($arr);
    }else{
      $default = collect([['value' => '0','text' => "--Select--"]]);
      $arr = AccAccountedFast::active()->orderBy('code','asc')->get();   
      $data = AccountedFastDropDownResource::collection($arr);
      $data = $default->merge($data)->values(); 
    }  

    return response()->json($data)->withCallback($request->input('callback'));
  }

}
