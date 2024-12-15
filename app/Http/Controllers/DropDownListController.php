<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\LicenseDropDownResource;
use App\Http\Resources\LangDropDownResource;
use App\Http\Resources\DropDownResource;
use App\Http\Resources\UsersDropDownResource;
use App\Http\Model\Country;
use App\Http\Model\Regions;
use App\Http\Model\Area;
use App\Http\Model\Distric;
use App\Http\Model\Software;
use App\Http\Model\Company;
use App\Http\Model\DocumentType;
use App\Http\Model\GroupUsers;
use App\Http\Model\License;
use App\Http\Model\Menu;
use App\Http\Model\User;

class DropDownListController extends Controller
{

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

  public function software_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(Software::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function company_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(Company::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function group_users_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = DropDownResource::collection(GroupUsers::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function user_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = UsersDropDownResource::collection(User::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function document_type_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(DocumentType::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function menu_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $type = Software::first();
    $data = LangDropDownResource::collection(Menu::get_raw_type($type->id));
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function menu_all_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LangDropDownResource::collection(Menu::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function license_dropdown_list(Request $request){
    $default = collect([['value' => '0','text' => "--Select--"]]);
    $data = LicenseDropDownResource::collection(License::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  // public function cost_code_dropdown_list(Request $request){
  //     $mysql2 = $request->session()->get('mysql2');
  //     config(['database.connections.mysql2' => $mysql2]);
  //     $data = DropDownListResource::collection(AccCostCode::active()->get());
  //     return response()->json($data);
  //}

  // public function statistical_code_dropdown_list(Request $request){
  //    $mysql2 = $request->session()->get('mysql2');
  //    config(['database.connections.mysql2' => $mysql2]);
  //    $data = DropDownListResource::collection(AccStatisticalCode::active()->get());
  //    return response()->json($data);
  // }

  // public function work_code_dropdown_list(Request $request){
  //   $mysql2 = $request->session()->get('mysql2');
  //   config(['database.connections.mysql2' => $mysql2]);
  //   $data = DropDownListResource::collection(AccWorkCode::active()->get());
  //   return response()->json($data);
  // }

// public function department_dropdown_list(Request $request){
//    $mysql2 = $request->session()->get('mysql2');
//    config(['database.connections.mysql2' => $mysql2]);
//    $data = DropDownListResource::collection(AccDepartment::active()->get());
//    return response()->json($data);
//}

// public function bank_account_dropdown_list(Request $request){
//    $mysql2 = $request->session()->get('mysql2');
//    config(['database.connections.mysql2' => $mysql2]);
//    $data = BankAccountDropDownListResource::collection(AccBankAccount::active()->get());
//    return response()->json($data);
//}

// public function debit_account_dropdown_list(Request $request){
//    $mysql2 = $request->session()->get('mysql2');
//    config(['database.connections.mysql2' => $mysql2]);
//    $setting_voucher = $request->session()->get('setting_voucher');
//    $request->session()->forget('setting_voucher');
//    $data = DropDownListResource::collection(AccAccountSystems::get_wherein_id(2,explode(',', $setting_voucher->debit_filter)));
//    return response()->json($data);
//}

// public function credit_account_dropdown_list(Request $request){
//    $mysql2 = $request->session()->get('mysql2');
//    config(['database.connections.mysql2' => $mysql2]);
//    $setting_voucher = $request->session()->get('setting_voucher');
//    $request->session()->forget('setting_voucher');
//    $data = DropDownListResource::collection(AccAccountSystems::get_wherein_id(2,explode(',', $setting_voucher->credit_filter)));
//    return response()->json($data);
//}

}
