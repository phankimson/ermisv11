<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\LicenseDropDownResource;
use App\Http\Resources\LangDropDownResource;
use App\Http\Resources\SoftwareDropDownResource;
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
  protected $default;
  public function __construct()
  {
    $this->default = ['value' => '0','text' => "--Select--"];
  }

  public function country_dropdown_list(Request $request){
      $default = collect([$this->default]);
      $data = DropDownResource::collection(Country::active()->get());
      $data = $default->merge($data)->values();
      return response()->json($data)->withCallback($request->input('callback'));
  }

   public function regions_dropdown_list(Request $request){
    $default = collect([$this->default]);
    $data = LangDropDownResource::collection(Regions::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
}

  public function area_dropdown_list(Request $request){
    $default = collect([$this->default]);
    $data = LangDropDownResource::collection(Area::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function distric_dropdown_list(Request $request){
    $default = collect([$this->default]);
    $data = LangDropDownResource::collection(Distric::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function software_dropdown_list(Request $request){
    $default = collect([$this->default]);
    $data = SoftwareDropDownResource::collection(Software::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function company_dropdown_list(Request $request){
    $default = collect([$this->default]);
    $data = DropDownResource::collection(Company::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function group_users_dropdown_list(Request $request){
    $default = collect([$this->default]);
    $data = DropDownResource::collection(GroupUsers::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function user_dropdown_list(Request $request){
    $default = collect([$this->default]);
    $data = UsersDropDownResource::collection(User::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function document_type_dropdown_list(Request $request){
    $default = collect([$this->default]);
    $data = LangDropDownResource::collection(DocumentType::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function menu_dropdown_list(Request $request){
    $default = collect([$this->default]);
    $type = Software::first();
    $data = LangDropDownResource::collection(Menu::get_raw_type($type->id));
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function menu_all_dropdown_list(Request $request){
    $default = collect([$this->default]);
    $data = LangDropDownResource::collection(Menu::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

  public function license_dropdown_list(Request $request){
    $default = collect([$this->default]);
    $data = LicenseDropDownResource::collection(License::active()->get());
    $data = $default->merge($data)->values();
    return response()->json($data)->withCallback($request->input('callback'));
  }

}
