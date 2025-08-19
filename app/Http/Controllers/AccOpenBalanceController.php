<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\Menu;
use App\Http\Model\Error;
use Illuminate\Support\Facades\Storage;
use App\Classes\Convert;
use App\Http\Model\AccAccountBalance;
use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccSystems;
use App\Http\Resources\OpenBalanceResource;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\DB;

class AccOpenBalanceController extends Controller
{
  protected $url;
  protected $key;
  protected $menu;
  protected $page_system;
  protected $download;
  protected $nature_none;
  public function __construct(Request $request)
  {
     $this->url =  $request->segment(3);
     $this->key = "open-balance";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->page_system = "MAX_COUNT_CHANGE_PAGE";
     $this->download = "AccOpenBalance.xlsx";
 }

  public function show(){
     $count = AccAccountBalance::count();
    $sys_page = AccSystems::get_systems($this->page_system);
    $paging = $count>$sys_page->value?1:0;   
    return view('acc.'.str_replace("-", "_", $this->key),['paging' => $paging, 'key' => $this->key ]);
  }

  public function data(){  
    $data = OpenBalanceResource::collection(AccAccountSystems::get_raw());       
    if($data){
      return response()->json($data);
    }else{
      return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
    }
  }


}
