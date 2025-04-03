<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\User;
use App\Http\Model\Menu;
use App\Http\Model\AccGeneral;
use App\Http\Model\AccDetail;
use App\Http\Model\AccSystems;
use App\Http\Model\CompanySoftware;
use App\Http\Model\Company;
use App\Http\Model\AccPrintTemplate;
use App\Http\Model\AccObject;
use App\Http\Model\Error;
use App\Classes\NumberConvert;
use App\Classes\Replace;
use Exception;

class AccGeneralController extends Controller
{
  protected $url;
  protected $key;
  protected $menu;
  public function __construct(Request $request)
 {
   $this->url =  $request->segment(3);
   $a = explode("/",$request->headers->get('referer'));
   $this->key = $a[5];
   $this->menu = Menu::where('code', '=', $this->key)->first();
 }

  public function detail(Request $request){
    $type = 10;
    try{
    $req = json_decode($request->data);
    $data = AccDetail::get_detail($req);
    if($data->count()>0){
      return response()->json(['status'=>true,'data'=> $data]);
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


    public function prints(Request $request) {
    $mysql2 = $request->session()->get(env('CONNECTION_DB_ACC'));
    $type = 10;
    try{
      $arr = json_decode($request->data);
      $print = AccPrintTemplate::find($arr->voucher);
      $general = AccGeneral::find($arr->id);
      $detail = AccDetail::get_detail($general->id)->scopeActive();
      $db = CompanySoftware::find($mysql2->database);
      $com = Company::find($db->company_id);
      $signer = AccSystems::get_systems_like('SIGNER_%');
      $object = AccObject::find($general->id);
      $debt = $detail->get('debt');
      $credit = $detail->get('credit');
      $user = User::find($general->user);
      //$locale = $this->app->getLocale();
      $locale = app()->getLocale();
      $letter = NumberConvert::ReadDecimal($general->total_amount,$locale,"đồng","đôla","xu","cent");
      $values = ['{company}' => $com->name ,
              '{company_address}' => $com->address,
              '{day}'=> $general->date_voucher->format('d'),
              '{month}'=> $general->date_voucher->format('m'),
              '{year}'=> $general->date_voucher->format('Y'),
              '{voucher}'=> $general->voucher,
              '{credit}'=>$credit,
              '{debt}'=>$debt,
              '{subject}'=> $object->name,
              '{address}'=> $object->address,
              '{reason}'=> $general->description,
              '{amount}'=> $general->total_amount,
              '{amount_letter}' => $letter,
              '{attach}'=> '1',
              '{day_voucher}' =>$general->date_voucher->format('d'),
              '{month_voucher}'=> $general->date_voucher->format('m'),
              '{year_voucher}'=> $general->date_voucher->format('Y'),
              '{director}'=> $signer->where('code', 'SIGNER_DIRECTOR')->value,
              '{chief_accountant}'=> $signer->where('code', 'SIGNER_CHIEF_ACCOUNTANT')->value,
              '{treasurer}'=> $signer->where('code', 'SIGNER_TREASURER')->value,
              '{writer}'=> $user->fullname,
              '{sender}'=> $object->name,
              '{exchange_rate}' =>$general->rate,
              '{amount_letter_exchange}'=>$general->total_amount_rate,
            ];
            $template = Replace::Array($print->content,$values);
            return response()->json(['status'=>true,'print_content'=> $template]);
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
      return response()->json(['status'=>false,'message'=> trans('messages.delete_fail').' '.$e->getMessage()]);
    }

    }

}
