<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\LangDropDownResource;
use App\Http\Resources\BankDropDownResource;
use App\Http\Resources\AccountedFastDropDownResource;
use App\Http\Resources\ObjectTypeDropDownResource;
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
use App\Http\Model\AccObjectGroup;
use App\Http\Model\AccAccountedFast;
use App\Http\Model\AccAccountType;
use App\Http\Model\AccAccountNature;
use App\Http\Model\AccNaturalResources;
use App\Http\Model\AccRevenueExpenditure;
use App\Http\Model\AccNumberVoucher;
use App\Http\Model\AccObjectType;
use App\Http\Model\Menu;
use App\Http\Model\Software;
use App\Http\Model\Country;
use App\Http\Model\Regions;
use App\Http\Model\Area;
use App\Http\Model\Distric;
use App\Http\Model\GroupUsers;
use App\Http\Model\AccSettingVoucher;

class AccDropDownListController extends Controller
{
  protected $document;
  protected $type;
  public function __construct()
  {
    $this->document = "DOCUMENT_TAX";
    $this->type = "acc";
  }
  public function country_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = DropDownResource::collection(Country::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function regions_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(Regions::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function area_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(Area::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function distric_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(Distric::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
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
  // Thuế Tài nguyên
  public function natural_resources_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccNaturalResources::active()->orderBy('code','asc')->get());
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
      $account = AccAccountSystems::active()->orderBy('code','asc')->doesntHave('account')->get();
    }else{
      $setting = AccSettingAccountGroup::get_code($code);
      $account = collect([]);
      if($setting && $setting->account_group){
        $account = AccAccountSystems::get_code_like($doc->id,$setting->account_group);
      }else if($setting && $setting->account_filter){
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

   // Mục thu chi
   public function revenue_expenditure_type_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccRevenueExpenditure::active()->orderBy('code','asc')->get());
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

  // Nhóm đối tượng
  public function object_group_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccObjectGroup::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // Loại đối tượng
  public function object_type_dropdown_list(Request $request){
    $data = ObjectTypeDropDownResource::collection(AccObjectType::active()->orderBy('code','asc')->get());
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

  // Loại Tài khoản
  public function account_type_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccAccountType::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

   // Tính chất Tài khoản
   public function account_nature_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccAccountNature::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // Tài khoản
  public function account_dropdown_list(Request $request){
    $multiple = $request->input('multiple',null);    
      if($multiple){
      $default = collect([['value' => '0','text' => "--Select--"]]);
      $data = LangDropDownResource::collection(AccAccountSystems::active()->orderBy('code','asc')->doesntHave('account')->get());
      $data = $default->merge($data)->values();
      }else{
       $data = LangDropDownResource::collection(AccAccountSystems::active()->orderBy('code','asc')->doesntHave('account')->get());
      }  
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // Tài khoản mặc định
  public function account_voucher_default_dropdown_list(Request $request){
     $menu = $request->input('menu',null);
     $type = $request->input('type',null);
     $default = collect(['value' => '0','text' => "--Select--"]);
     $setting_voucher = AccSettingVoucher::get_menu($menu);
     $data = [];
     if($type == 1){ // Mặc định debit
        if($setting_voucher->credit == 0){
          $debt_default =  $default;
        }else{
          $debt_default = new LangDropDownResource(AccAccountSystems::find($setting_voucher->debit));
        }   
        $data =  $debt_default;
     }else if ($type == 2){ // Mặc định credit
        if($setting_voucher->credit == 0){
          $credit_default =  $default;
        }else{
          $credit_default = new LangDropDownResource(AccAccountSystems::find($setting_voucher->credit));
        } 
        $data = $credit_default;
     }
     return response()->json($data)->withCallback($request->input('callback'));
  }

  // Tài khoản nhóm
  public function account_voucher_filter_dropdown_list(Request $request){
    $menu = $request->input('menu',null);
    $type = $request->input('type',null);
    $setting_voucher = AccSettingVoucher::get_menu($menu);
    $sys = AccSystems::get_systems($this->document);
    $document = Document::get_code($sys->value);
    $data = [];
    if($type == 1){ // Def
      $debt_account = LangDropDownResource::collection(AccAccountSystems::get_wherein_id($document->id,$setting_voucher->debit_filter));
      $data = $debt_account;
    }else if ($type == 2){
      $credit_account = LangDropDownResource::collection(AccAccountSystems::get_wherein_id($document->id,$setting_voucher->credit_filter));
      $data = $credit_account;
    }    
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // Tài khoản mặc định

  // Group User
  public function group_user_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(GroupUsers::active()->orderBy('code','asc')->get());
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

   // Số chứng từ
   public function number_voucher_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(AccNumberVoucher::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

   // Danh mục ACC
   public function menu_dropdown_list(Request $request){
    $type = Software::get_url($this->type);
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(Menu::get_raw_type($type->id));
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

   // Tài liệu
   public function document_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(Document::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

}
