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
use App\Http\Model\AccPrintTemplate;
use App\Http\Model\AccObject;
use App\Http\Model\Error;
use App\Http\Resources\GeneralDetailResource;
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
    $data = GeneralDetailResource::collection(AccDetail::get_detail($req));
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
        'error' => $e->getMessage().' - Line '.$e->getLine(),
        'url'  => $this->url,
        'check' => 0 ]);
      return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage().' - Line '.$e->getLine()]);
    }
  }


    public function prints(Request $request) {
    $type = 10;
    try{
      $arr = json_decode($request->data);
      $print = AccPrintTemplate::find($arr->voucher);
      $general = AccGeneral::find($arr->id);
      $detail = AccDetail::get_detail_scopes_active($general->id);
      $com = $request->session()->get("com");
      $signer = AccSystems::get_systems_like('SIGNER_%');
      $object = AccObject::find($general->subject);
      $debit = $detail->pluck('debit')->join(",");
      $credit = $detail->pluck('credit')->join(",");
      $user = User::find($general->user);
      //$locale = $this->app->getLocale();
      $locale = app()->getLocale();
      $letter = NumberConvert::ReadDecimal($general->total_amount,$locale,"đồng","đôla","xu","cent");
      $voucher_date =  strtotime($general->voucher_date);
      $voucher_day = date('d', $voucher_date);     
      $voucher_month = date('m', $voucher_date);
      $voucher_year = date('Y', $voucher_date);
      $values = ['{company}' => $com->name ,
              '{company_address}' => $com->address,
              '{day}'=> $voucher_day,
              '{month}'=> $voucher_month,
              '{year}'=> $voucher_year,
              '{voucher}'=> $general->voucher,
              '{credit}'=>$credit,
              '{debt}'=>$debit,
              '{subject}'=> $object->name,
              '{address}'=> $object->address,
              '{reason}'=> $general->description,
              '{amount}'=> number_format($general->total_amount),
              '{amount_letter}' => $letter,
              '{attach}'=> '1',
              '{day_voucher}' =>$voucher_day,
              '{month_voucher}'=> $voucher_month,
              '{year_voucher}'=> $voucher_year,
              '{director}'=> $signer->where('code', 'SIGNER_DIRECTOR')->first()->value,
              '{chief_accountant}'=> $signer->where('code', 'SIGNER_CHIEF_ACCOUNTANT')->first()->value,
              '{treasurer}'=> $signer->where('code', 'SIGNER_TREASURER')->first()->value,
              '{writer}'=> $user->fullname,
              '{sender}'=> $object->name,
              '{exchange_rate}' =>$general->rate,
              '{amount_letter_exchange}'=>$general->total_amount_rate,
            ];
            $template = Replace::ArrayKey($print->content,$values);
            return response()->json(['status'=>true,'print_content'=> $template]);
    }catch(Exception $e){
      // Lưu lỗi
      $err = new Error();
      $err ->create([
        'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
        'user_id' => Auth::id(),
        'menu_id' => $this->menu->id,
        'error' => $e->getMessage().' - Line '.$e->getLine(),
        'url'  => $this->url,
        'check' => 0 ]);
      return response()->json(['status'=>false,'message'=> trans('messages.print_fail').' '.$e->getMessage().' - Line '.$e->getLine()]);
    }

    }

}
