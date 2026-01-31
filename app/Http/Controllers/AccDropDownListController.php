<?php

namespace App\Http\Controllers;

use App\Classes\Convert;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\LangDropDownResource;
use App\Http\Resources\SuppliesGoodsIssueDropDownResource;
use App\Http\Resources\SuppliesGoodsReceiptDropDownResource;
use App\Http\Resources\AccountSystemsDropDownResource;
use App\Http\Resources\LangTaxDropDownResource;
use App\Http\Resources\BankDropDownResource;
use App\Http\Resources\BankMultiDropDownResource;
use App\Http\Resources\AccountedFastDropDownResource;
use App\Http\Resources\ObjectTypeDropDownResource;
use App\Http\Resources\ObjectDropDownListResource;
use App\Http\Resources\DropDownResource;
use App\Http\Resources\TaxDropDownResource;
use App\Http\Model\AccUnit;
use App\Http\Model\AccSuppliesGoods;
use App\Http\Model\AccSuppliesGoodsType;
use App\Http\Model\AccSuppliesGoodsGroup;
use App\Http\Model\AccWarrantyPeriod;
use App\Http\Model\AccStock;
use App\Http\Model\AccVat;
use App\Http\Model\AccExcise;
use App\Http\Model\Document;
use App\Http\Model\AccSettingAccountGroup;
use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccCaseCode;
use App\Http\Model\AccCostCode;
use App\Http\Model\AccStatisticalCode;
use App\Http\Model\AccWorkCode;
use App\Http\Model\AccBankAccount;
use App\Http\Model\AccBank;
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
use App\Http\Traits\LoadDocumentTraits;

class AccDropDownListController extends Controller
{
  use LoadDocumentTraits;
  protected $document;
  protected $type;
  protected $default;
  protected $default_multi;
  public function __construct()
  {
    $this->document = "DOCUMENT_TAX";
    $this->type = "acc";
    $this->default = ["value" => "0","text" => "--Select--"];
    $this->default_multi = ["value" => "0","text" => "--Select--", "description" => ""];
  }
  public function country_dropdown_list(Request $request){
     $val = $request->input('value',null); 
    if($val != null){
      $rs = Country::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new DropDownResource($rs);
      }      
    }else{
    $default = collect([$this->default]);
    $data = DropDownResource::collection(Country::active()->get());
    $data = $default->merge($data)->values();
    } 
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function regions_dropdown_list(Request $request){
    $val = $request->input('value',null); 
    if($val != null){
      $rs = Regions::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
    $default = collect([$this->default]);
    $data = LangDropDownResource::collection(Regions::active()->get());
    $data = $default->merge($data)->values();
    } 
    
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function area_dropdown_list(Request $request){
     $val = $request->input('value',null); 
    if($val != null){
      $rs = Area::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
    $default = collect([$this->default]);
    $data = LangDropDownResource::collection(Area::active()->get());
    $data = $default->merge($data)->values();
    }    
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function distric_dropdown_list(Request $request){
    $val = $request->input('value',null); 
    if($val != null){
      $rs = Distric::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
    $default = collect([$this->default]);
    $data = LangDropDownResource::collection(Distric::active()->get());
    $data = $default->merge($data)->values();
    }       
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // Đơn vị tính Droplist
  public function unit_dropdown_list(Request $request){
    $val = $request->input('value',null); 
    if($val != null){
      $rs = AccUnit::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
    $default = collect([$this->default]);
    $data = LangDropDownResource::collection(AccUnit::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    }         
    return response()->json($data)->withCallback($request->input('callback'));
  }
  // Loại hàng hóa Droplist
  public function supplies_goods_type_dropdown_list(Request $request){
    $val = $request->input('value',null); 
    if($val != null){
      $rs = AccSuppliesGoodsType::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
    $default = collect([$this->default]);
    $data = LangDropDownResource::collection(AccSuppliesGoodsType::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    }     
    return response()->json($data)->withCallback($request->input('callback'));
  }
  // Nhóm hàng hóa Droplist
  public function supplies_goods_group_dropdown_list(Request $request){
    $val = $request->input('value',null); 
    if($val != null){
      $rs = AccSuppliesGoodsGroup::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
    $default = collect([$this->default]);
    $data = LangDropDownResource::collection(AccSuppliesGoodsGroup::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    }         
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // Hàng hóa Droplist
  public function supplies_goods_dropdown_list(Request $request){
    $val = $request->input('value',null); 
    $stock = $request->input('stock',null); 
    $price = $request->input('price',null); 
    if($val != null){
      $rs = AccSuppliesGoods::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else if($stock != null){
      $rs = AccSuppliesGoods::get_has_stock($stock);
      if($stock == "none"){
        $data = SuppliesGoodsReceiptDropDownResource::collection($rs);  
      }else{
        $rs_convert = Convert::Array_convert_supplies_goods($rs,$price);
        $data = SuppliesGoodsIssueDropDownResource::collection($rs_convert);  
      }    
   
    }else{
    $default = collect([$this->default]);
    $data = LangDropDownResource::collection(AccSuppliesGoods::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    }         
    return response()->json($data)->withCallback($request->input('callback'));
  }
  // Bảo hành Droplist
  public function warranty_period_dropdown_list(Request $request){
    $val = $request->input('value',null); 
    if($val != null){
      $rs = AccWarrantyPeriod::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
    $default = collect([$this->default]);
    $data = LangDropDownResource::collection(AccWarrantyPeriod::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    }     
    return response()->json($data)->withCallback($request->input('callback'));
  }
   // Kho Droplist
  public function stock_dropdown_list(Request $request){
     $val = $request->input('value',null); 
    // Lấy tất cả tài khoản
    $df = $request->input('default',null);   
    if($val != null){
      $rs = AccStock::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else if($df != null){
    $data = LangDropDownResource::collection(AccStock::active()->orderBy('code','asc')->get());
    }else{
    $default = collect([$this->default]);
    $data = LangDropDownResource::collection(AccStock::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    }     
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // Loại hóa đơn Droplist
  public function invoice_type_dropdown_list(Request $request){
    $default = collect([$this->default]);
    $data = collect([["value" => "1","text" => trans('acc_voucher.input_invoice')],["value" => "2","text" => trans('acc_voucher.output_invoice')]]);
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // Thuế VAT Droplist
  public function vat_tax_dropdown_list(Request $request){
    $default = collect([$this->default]);
    $type = $request->input('type',null); 
    if($type){
      $data = LangTaxDropDownResource::collection(AccVat::active()->orderBy('code','asc')->get());
    }else{
      $data = TaxDropDownResource::collection(AccVat::active()->orderBy('code','asc')->get());
    }    
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // Thuế TTDB Droplist
  public function excise_tax_dropdown_list(Request $request){
    $val = $request->input('value',null); 
    if($val != null){
      $rs = AccExcise::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
    $default = collect([$this->default]);
    $data = LangDropDownResource::collection(AccExcise::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    }    
    return response()->json($data)->withCallback($request->input('callback'));
  }
  // Thuế Tài nguyên
  public function natural_resources_dropdown_list(Request $request){
     $val = $request->input('value',null); 
    if($val != null){
      $rs = AccNaturalResources::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
    $default = collect([$this->default]);
    $data = LangDropDownResource::collection(AccNaturalResources::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    }    
    return response()->json($data)->withCallback($request->input('callback'));
  }
  // Lấy tài khoản nhóm theo mã
  public function setting_account_group_dropdown_list(Request $request){
    $default = collect([$this->default]);
    $doc = $this->getDoc($this->document);
    $code = $request->input('code',null);
    if($code == null){
      $account = AccAccountSystems::get_all_not_parent($doc->id);
    }else{
      $setting = AccSettingAccountGroup::get_code($code);
      $account = collect([]);
      if($setting && $setting->account_group){
        $account = AccAccountSystems::get_code_like($doc->id,$setting->account_group);
      }else if($setting && $setting->account_filter){
        $account = AccAccountSystems::get_wherein_id($doc->id,$setting->account_filter->pluck('account_systems'));
      }else{

      }    
    } 
    $data = LangDropDownResource::collection($account);
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }
   // Mã vụ việc
   public function case_code_dropdown_list(Request $request){
    $val = $request->input('value',null); 
    if($val != null){
      $rs = AccCaseCode::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
    $default = collect([$this->default]);
    $data = LangDropDownResource::collection(AccCaseCode::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    }
    return response()->json($data)->withCallback($request->input('callback'));
  }

   // Mã chi phí
   public function cost_code_dropdown_list(Request $request){
    $val = $request->input('value',null); 
    if($val != null){
      $rs = AccCostCode::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
    $default = collect([$this->default]);
    $data = LangDropDownResource::collection(AccCostCode::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    }
    return response()->json($data)->withCallback($request->input('callback'));
  }

   // Mục thu chi
   public function revenue_expenditure_type_dropdown_list(Request $request){
    $val = $request->input('value',null); 
    if($val != null){
      $rs = AccRevenueExpenditure::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
    $default = collect([$this->default]);
    $data = LangDropDownResource::collection(AccRevenueExpenditure::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    }
    return response()->json($data)->withCallback($request->input('callback'));
  }

   // Mã thống kê
   public function statistical_code_dropdown_list(Request $request){
    $val = $request->input('value',null); 
    if($val != null){
      $rs = AccStatisticalCode::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
     $default = collect([$this->default]);
    $data = LangDropDownResource::collection(AccStatisticalCode::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    }
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // Mã công việc
  public function work_code_dropdown_list(Request $request){
    $val = $request->input('value',null); 
    if($val != null){
      $rs = AccWorkCode::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
      $default = collect([$this->default]);
      $data = LangDropDownResource::collection(AccWorkCode::active()->orderBy('code','asc')->get());
      $data = $default->merge($data)->values();
    }  
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // TK Ngân hàng
  public function bank_account_dropdown_list(Request $request){
    $val = $request->input('value',null); 
    $detail = $request->input('detail',null); 
    if($val != null){
      $rs = AccBankAccount::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new BankDropDownResource($rs);
      }      
    }else if($detail != null){
      $default = collect([$this->default_multi]);
      $rs = AccBankAccount::get_has_detail();
      $data = BankMultiDropDownResource::collection($rs);
      $data = $default->merge($data)->values();
    }else{
      $default = collect([$this->default]);
      $data = BankDropDownResource::collection(AccBankAccount::active()->orderBy('bank_account','asc')->get());
      $data = $default->merge($data)->values();
    }  
    return response()->json($data)->withCallback($request->input('callback'));
  }

   // Ngân hàng
  public function bank_dropdown_list(Request $request){
    $val = $request->input('value',null); 
    if($val != null){
      $rs = AccBank::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
    $default = collect([$this->default]);
    $data = LangDropDownResource::collection(AccBank::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    }         
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // Nhóm đối tượng
  public function object_group_dropdown_list(Request $request){
    $val = $request->input('value',null); 
    if($val != null){
      $rs = AccObjectGroup::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
    $default = collect([$this->default]);
    $data = LangDropDownResource::collection(AccObjectGroup::active()->orderBy('code','asc')->get());
    $data = $default->merge($data)->values();
    }
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // Loại đối tượng
  public function object_type_dropdown_list(Request $request){
    $data = ObjectTypeDropDownResource::collection(AccObjectType::active()->orderBy('code','asc')->get());
    return response()->json($data)->withCallback($request->input('callback'));
  }

   // Đối tượng
   public function object_dropdown_list(Request $request){
     $val = $request->input('value',null); 
    if($val != null){
      $rs = AccObject::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new ObjectDropDownListResource($rs);
      }      
    }else{
      $default = collect([$this->default]);
      $data = ObjectDropDownListResource::collection(AccObject::active()->orderBy('code','asc')->get());
      $data = $default->merge($data)->values();
    }    
    return response()->json($data)->withCallback($request->input('callback'));
  }

   // Bộ phận
   public function department_dropdown_list(Request $request){
    $val = $request->input('value',null); 
    if($val != null){
      $rs = AccDepartment::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
      $default = collect([$this->default]);
      $data = LangDropDownResource::collection(AccDepartment::active()->orderBy('code','asc')->get());
      $data = $default->merge($data)->values();
    }      
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // Loại Tài khoản
  public function account_type_dropdown_list(Request $request){
    $val = $request->input('value',null); 
    if($val != null){
      $rs = AccAccountType::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
      $default = collect([$this->default]);
      $data = LangDropDownResource::collection(AccAccountType::active()->orderBy('code','asc')->get());
      $data = $default->merge($data)->values();
    }   
    return response()->json($data)->withCallback($request->input('callback'));
  }

   // Tính chất Tài khoản
   public function account_nature_dropdown_list(Request $request){
    $val = $request->input('value',null); 
    if($val != null){
      $rs = AccAccountNature::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
      $default = collect([$this->default]);
      $data = LangDropDownResource::collection(AccAccountNature::active()->orderBy('code','asc')->get());
      $data = $default->merge($data)->values();
    }   
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // List tài khoản
  public function account_dropdown_list(Request $request){
    // Lấy tài khoản theo tính chất
    $nature = $request->input('nature',null);
    // Lấy tài khoản không có default
    $multiple = $request->input('multiple',null);
    // Lấy tất cả tài khoản
    $full = $request->input('full',null);     
    // Lấy default document
    $document = $this->getDoc($this->document); 
      if($multiple){
       $data = LangDropDownResource::collection(AccAccountSystems::get_all_not_parent($document->id));
      }else if($full){
        $default = collect([$this->default]);
        $data = LangDropDownResource::collection(AccAccountSystems::get_all($document->id));
        $data = $default->merge($data)->values();  
      }else if($nature){
        $default = collect([$this->default]);
        $account_nature = AccAccountNature::get_code($nature);
        $data = LangDropDownResource::collection(AccAccountSystems::get_nature($document->id,$account_nature->id));
        $data = $default->merge($data)->values();  
      }else{
        $default = collect([$this->default]);
        $data = LangDropDownResource::collection(AccAccountSystems::get_all_not_parent($document->id));
        $data = $default->merge($data)->values();       
      }  
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // Tài khoản mặc định
  public function account_voucher_default_dropdown_list(Request $request){
     $menu = $request->input('menu',null);
     $type = $request->input('type',null);    
      $default = collect($this->default);
      $setting_voucher = AccSettingVoucher::get_menu($menu);
      $data = [];
      if($type == 1){ // Mặc định debit
         if($setting_voucher->debit == 0){
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
      }else if ($type == 3){ // Mặc định vat account
         if($setting_voucher->vat_account == 0){
           $vat_account_default =  $default;
         }else{
           $vat_account_default = new LangDropDownResource(AccAccountSystems::find($setting_voucher->vat_account));
         } 
         $data = $vat_account_default;
      }else{

      }      
     
     return response()->json($data)->withCallback($request->input('callback'));
  }

  // Tài khoản nhóm
  public function account_voucher_filter_dropdown_list(Request $request){
    $menu = $request->input('menu',null);
    $type = $request->input('type',null);
    $val = $request->input('value',null);   
    $option = $request->input('option',null);  
    $setting_voucher = AccSettingVoucher::get_menu($menu);
    $document = $this->getDoc($this->document);
    $data = collect();
    if($type == 1){ // Def
      if($option){
        $debt_account = AccountSystemsDropDownResource::collection(AccAccountSystems::get_wherein_id($document->id,$setting_voucher->debit_filter));
      }else{
        $debt_account = LangDropDownResource::collection(AccAccountSystems::get_wherein_id($document->id,$setting_voucher->debit_filter));
      }      
      $data = $debt_account;
    }else if ($type == 2){
      if($option){
        $credit_account = AccountSystemsDropDownResource::collection(AccAccountSystems::get_wherein_id($document->id,$setting_voucher->credit_filter));
      }else{
        $credit_account = LangDropDownResource::collection(AccAccountSystems::get_wherein_id($document->id,$setting_voucher->credit_filter));
      }
      
      $data = $credit_account;
    }else{
      
    }  
    if($val != null){
      $data = $data->filter(function ($item) use ($val) {
        return $item->id == $val;
     })->first();
    }
    return response()->json($data)->withCallback($request->input('callback'));
  }  

  // Group User
  public function group_user_dropdown_list(Request $request){
    $val = $request->input('value',null); 
    if($val != null){
      $rs = GroupUsers::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
      $default = collect([$this->default]);
      $data = LangDropDownResource::collection(GroupUsers::active()->orderBy('code','asc')->get());
      $data = $default->merge($data)->values();
    }   
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // Hạch toán nhanh
  public function accounted_fast_dropdown_list(Request $request){    
    $val = $request->input('value',null);
    $pro = $request->input('pro',null);
    if($val != null){
      $arr = AccAccountedFast::find($val);   
      $data = new AccountedFastDropDownResource($arr);
    }else if($pro != null){
      $default = collect([$this->default]);
      $arr = AccAccountedFast::get_profession($pro);   
      $data = AccountedFastDropDownResource::collection($arr);
      $data = $default->merge($data)->values(); 
    }else{
      $default = collect([$this->default]);
      $arr = AccAccountedFast::active()->orderBy('code','asc')->get();   
      $data = AccountedFastDropDownResource::collection($arr);
      $data = $default->merge($data)->values(); 
    }  
    return response()->json($data)->withCallback($request->input('callback'));
  }

   // Số chứng từ
   public function number_voucher_dropdown_list(Request $request){
     $val = $request->input('value',null); 
    if($val != null){
      $rs = AccNumberVoucher::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
      $default = collect([$this->default]);
      $data = LangDropDownResource::collection(AccNumberVoucher::active()->orderBy('code','asc')->get());
      $data = $default->merge($data)->values();
    }
    return response()->json($data)->withCallback($request->input('callback'));
  }

   // Danh mục ACC
   public function menu_dropdown_list(Request $request){
    $type = Software::get_url($this->type);
    $pro = $request->input('pro',null); 
    $array = explode(",", $pro);
    $default = collect([$this->default]);
    if($pro){
      $data = LangDropDownResource::collection(Menu::get_menu_by_where_in_group($type->id,$array));
    }else{
      $data = LangDropDownResource::collection(Menu::get_raw_type($type->id));
    }   

    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

   // Tài liệu
   public function document_dropdown_list(Request $request){
     $val = $request->input('value',null); 
    if($val != null){
      $rs = Document::find($val);
      if(!$rs){
        $data = collect($this->default);
      }else{
        $data = new LangDropDownResource($rs);
      }      
    }else{
      $default = collect([$this->default]);
      $data = LangDropDownResource::collection(Document::active()->orderBy('code','asc')->get());
      $data = $default->merge($data)->values();
    }    
    return response()->json($data)->withCallback($request->input('callback'));
  }

}
