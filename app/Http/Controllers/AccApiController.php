<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Illuminate\Support\Str;
//use App\Http\Model\AccCaseCode;
//use App\Http\Model\AccCostCode;
//use App\Http\Model\AccStatisticalCode;
//use App\Http\Model\AccWorkCode;
//use App\Http\Model\AccDepartment;
//use App\Http\Model\AccBankAccount;
//use App\Http\Model\AccAccountSystems;
//use App\Http\Resources\DropDownListResource;
//use App\Http\Resources\BankAccountDropDownListResource;

class AccApiController extends Controller
{

  //  public function case_code_dropdown_list(Request $request){
  //      $mysql2 = $request->session()->get('mysql2');
  //      config(['database.connections.mysql2' => $mysql2]);
  //      $data = DropDownListResource::collection(AccCaseCode::active()->get());
  //      return response()->json($data);
  // }

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
