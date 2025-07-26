<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\Error;
use App\Http\Model\AccGeneral;
use App\Http\Model\Menu;
use App\Http\Resources\BankLoadReadResource;
use Exception;

class AccBankReconciliationController extends Controller
{
  protected $url;
  protected $key;
  protected $menu;
  protected $group;
  protected $page_system;
  public function __construct(Request $request)
  {
     $this->url =  $request->segment(3);
     $this->key = "bank-reconciliation";   
     $this->group = [3,4]; // 3,4 Thu chi bank 
     $this->menu = Menu::where('code', '=', $this->key)->first();
  }

  public function show(){
    return view('acc.bank_reconciliation',['key' => $this->key ]);
  }

  public function load(Request $request){
    $type = 10;
    try{
      $req = json_decode($request->data);
      $data1 = BankLoadReadResource::collection(AccGeneral::get_data_load_group_bank_account_between($this->group,$req->start_date,$req->end_date,$req->bank_account,$req->active));
      if($data1->count()>0){
        return response()->json(['status'=>true,'data1'=> $data1]);
      }else{
        return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
      }
     }catch(Exception $e){
        // Lưu lỗi
        $err = new Error();
        $err ->create([
          'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
          'user_id' => Auth::id(),
          'menu_id' => $this->menu->id,
          'error' => $e->getMessage(),
          'url'  => $this->url,
          'check' => 0 ]);
        return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
      }
  }

}
