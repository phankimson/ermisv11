<?php

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
//Route::get('/test', function () {
//    return 'Hello World';
//});
Route::prefix('manage')->group(function () {
// DropDownList
Route::group([
    'as' => 'dropdownlist',
   'controller' => DropDownListController::class
  ],function () {
  Route::get(env('URL_DROPDOWN').'/country', 'country_dropdown_list')->name('manage.dropdownlist.country');
  Route::get(env('URL_DROPDOWN').'/regions', 'regions_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/area', 'area_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/distric', 'distric_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/software', 'software_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/company', 'company_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/group-users', 'group_users_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/document-type', 'document_type_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/menu', 'menu_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/menu-all', 'menu_all_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/license', 'license_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/user', 'user_dropdown_list');
  });
}); 

Route::prefix('acc')->group(function () {
    // DropDownList
   Route::group([
    'as' => 'dropdownlist',
   'controller' => AccDropDownListController::class
  ],function () {
  Route::get(env('URL_DROPDOWN').'/unit', 'unit_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/supplies-goods-type', 'supplies_goods_type_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/supplies-goods-group', 'supplies_goods_group_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/warranty-period', 'warranty_period_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/stock', 'stock_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/vat-tax', 'vat_tax_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/excise-tax', 'excise_tax_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/natural-resources', 'natural_resources_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/setting-account-group', 'setting_account_group_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/case-code', 'case_code_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/cost-code', 'cost_code_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/statistical-code', 'statistical_code_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/work-code', 'work_code_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/bank-account', 'bank_account_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/object', 'object_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/object-group', 'object_group_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/object-type', 'object_type_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/menu', 'menu_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/number-voucher', 'number_voucher_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/department', 'department_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/account', 'account_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/account-voucher-default', 'account_voucher_default_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/account-voucher-filter', 'account_voucher_filter_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/account-type', 'account_type_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/account-nature', 'account_nature_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/accounted-fast', 'accounted_fast_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/revenue-expenditure-type', 'revenue_expenditure_type_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/document', 'document_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/country', 'country_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/regions', 'regions_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/area', 'area_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/distric', 'distric_dropdown_list');
  Route::get(env('URL_DROPDOWN').'/group-user', 'group_user_dropdown_list');
  });
});