<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\User;
use App\Http\Model\Menu;
use App\Http\Model\AccGeneral;
use App\Http\Model\AccDetail;
use App\Http\Model\AccVatDetail;
use App\Http\Model\AccAttach;
use App\Http\Model\AccPeriod;
use App\Http\Model\AccSystems;
use App\Http\Model\CompanySoftware;
use App\Http\Model\Company;
use App\Http\Model\AccPrintTemplate;
use App\Http\Model\AccObject;
use App\Http\Model\Error;
use App\Classes\NumberConvert;
use App\Classes\Replace;
use Carbon\Carbon;
use File;

class AccGeneralController extends Controller
{
  public function __construct(Request $request)
 {
   $this->url =  $request->segment(3);
   $key = explode("/",$request->headers->get('referer'));
   $this->key = $key[5];
   $this->menu = Menu::where('code', '=', $this->key)->first();
 }

  public function detail(Request $request){
    $type = 10;
    try{
    $mysql2 = $request->session()->get('mysql2');
    config(['database.connections.mysql2' => $mysql2]);
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


  public function delete(Request $request) {
    $mysql2 = $request->session()->get('mysql2');
    config(['database.connections.mysql2' => $mysql2]);
    $type = 4;
       try{
         $permission = $request->session()->get('per');
         $arr = json_decode($request->data);
         if($arr){
          $data = AccGeneral::get_id_with_detail($arr);
           $period = AccPeriod::get_date(Carbon::parse($data->accounting_date)->format('Y-m'),1);
           if(!$period){
             if($permission['d'] == true){             

               // Lưu lịch sử
               $h = new AccHistoryAction();
               $h ->create([
               'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
               'user' => Auth::id(),
               'menu' => $this->menu->id,
               'url'  => $this->url,
               'dataz' => \json_encode($data)]);
               //
               $data->delete();

               $detail = AccDetail::get_detail($arr);

               // Xóa các dòng
               if($detail->count()>0){
                 $id_destroy = $detail->pluck('id');
                 AccDetail::destroy($id_destroy);
               }
               ///////////////////

               $vat = AccVatDetail::get_detail($arr);
               // Xóa các dòng
               if($vat->count()>0){
                 $id_destroy_vat = $vat->pluck('id');
                 AccVatDetail::destroy($id_destroy_vat);
               }
               ///////////////////

               $attach = AccAttach::get_detail($arr);
               foreach($attach as $a){
                 //Xóa ảnh cũ
                 if(File::exists(public_path($a->path))){
                    File::delete(public_path($a->path));
                 };
                 $a->delete();
               };
               return response()->json(['status'=>true,'message'=> trans('messages.delete_success')]);
           }else{
             return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission_delete')]);
           }
         }else{
           return response()->json(['status'=>false,'message'=> trans('messages.locked_period')]);
         }
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
         return response()->json(['status'=>false,'message'=> trans('messages.delete_fail').' '.$e->getMessage()]);
       }
  }

    public function prints(Request $request) {
    $mysql2 = $request->session()->get('mysql2');
    config(['database.connections.mysql2' => $mysql2]);
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
      $locale = $this->app->getLocale();
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
