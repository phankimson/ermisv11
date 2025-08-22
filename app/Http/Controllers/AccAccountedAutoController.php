<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\Menu;
use App\Http\Model\AccAccountedAuto;
use App\Http\Model\AccAccountedAutoDetail;
use App\Http\Model\AccSystems;
use App\Http\Model\CompanySoftware;
use App\Http\Model\Company;
use App\Http\Model\AccNumberCode;
use App\Http\Model\Error;
use Illuminate\Support\Facades\Storage;
use App\Http\Model\Imports\AccAccountedAutoImport;
use App\Http\Model\Exports\AccAccountedAutoExport;
use App\Classes\Convert;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\DB;

class AccAccountedAutoController extends Controller
{
  protected $url;
  protected $key;
  protected $menu;
  protected $page_system;
  protected $download;
  protected $pro;
  public function __construct(Request $request)
  {
     $this->url =  $request->segment(3);
     $this->key = "accounted-auto";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->page_system = "MAX_COUNT_CHANGE_PAGE";
     $this->pro = "1,2,3,4,5,6,7,8,9,10";
     $this->download = "AccAccountedAuto.xlsx";
 }

  public function show(){
    //$data = AccAccountedAuto::with('accounted_auto_detail')->get();
    $count = AccAccountedAuto::count();
    $sys_page = AccSystems::get_systems($this->page_system);
    $paging = $count>$sys_page->value?1:0; 
    //$account = json_encode(LangDropDownListResource::collection(AccAccountSystems::active()->OrderBy('code','asc')->doesntHave('account')->get()));
    //$work_code = json_encode(collect(LangDropDownListResource::collection(AccWorkCode::active()->OrderBy('code','asc')->get())));
    //$department = json_encode(collect(LangDropDownListResource::collection(AccDepartment::active()->OrderBy('code','asc')->get())));
    //$bank_account = json_encode(collect(BankAccountDropDownListResource::collection(AccBankAccount::active()->OrderBy('bank_account','asc')->get())));
    //$case_code = json_encode(collect(LangDropDownListResource::collection(AccCaseCode::active()->OrderBy('code','asc')->get())));
    //$cost_code = json_encode(collect(LangDropDownListResource::collection(AccCostCode::active()->OrderBy('code','asc')->get())));
    //$statistical_code = json_encode(collect(LangDropDownListResource::collection(AccStatisticalCode::active()->OrderBy('code','asc')->get())));
    //$accounted_fast = json_encode(collect(AccountedFastDropDownListResource::collection(AccAccountedFast::active()->OrderBy('code','asc')->get())));
    //$object = json_encode(collect(DropDownListResource::collection(AccObject::active()->get())));
    return view('acc.'.str_replace("-", "_", $this->key),['paging' => $paging,'key' => $this->key,'profession' => $this->pro]);
  }


  public function data(Request $request){   
    $total = AccAccountedAuto::count();
    $sys_page = AccSystems::get_systems($this->page_system);   
    $perPage = $request->input('$top',$sys_page->value);
    $skip = $request->input('$skip',0);
    $orderby =   $request->input('$orderby','created_at desc');
    $filter =   $request->input('$filter');
    $asc  = 'desc';
        if (!str_contains($orderby, 'desc')) { 
          $asc = 'asc';
        }else{
          $orderby = explode(' ', $orderby)[0];
        };
        if($filter){
          $filter_sql = Convert::filterRow($filter);
          $arr = AccAccountedAuto::get_raw_skip_filter_page($skip,$perPage,$orderby,$asc,$filter_sql);
          $total = AccAccountedAuto::whereRaw($filter_sql)->count();
        }else{
          $arr = AccAccountedAuto::get_raw_skip_page($skip,$perPage,$orderby,$asc); 
        } 
    $data = collect(['data' => $arr,'total' => $total]);              
    if($data){
      return response()->json($data);
    }else{
      return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
    }
  }

  public function load(){
    $type = 10;
    try{
    $data = AccNumberCode::get_code($this->key);
    if($data){
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


  public function ChangeDatabase(Request $request){
    $type = 9;
    try{
      $req = json_decode($request->data);
      $db = CompanySoftware::find($req->database);
      $com = Company::find($db->company_id);
      $params = array(
            'driver'    => env('DB_CONNECTION', 'mysql'),
            'host'      => env('DB_HOST', '127.0.0.1'),
            'database'  => $db->database,
            'username'  => $db->username,
            'password'  => $db->password,
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        );
      $request->session()->put(env('CONNECTION_DB_ACC'), $params);
      config(['database.connections.mysql2' => $params]);
      $data = AccAccountedAuto::with('accounted_auto_detail')->get();
      return response()->json(['status'=>true,'data'=> $data,'com_name'=> $com->name ]);
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

  public function save(Request $request){
    $type = 0;
    try{
   DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
  $permission = $request->session()->get('per');
  $arr = json_decode($request->data);
  $hot = $arr->hot;
  $validator = Validator::make(collect($arr)->toArray(),[
            'code' => ['required','max:50'],
            'name' => 'required',
        ]);
     if($validator->passes()){
      $code_check = AccAccountedAuto::WhereCheck('code',$arr->code,'id',$arr->id)->first();
      if($code_check == null){
     if($permission['a'] == true && !$arr->id ){
       $type = 2;
       $data = new AccAccountedAuto();
       $data->profession = $arr->profession;
       $data->code = $arr->code;
       $data->name = $arr->name;
       $data->name_en = $arr->name_en;
       $data->description = $arr->description;
       $data->active = $arr->active;
       $data->save();


       // Lưu mã code tự tăng
       $ir = AccNumberCode::get_code($this->key);
       $ir->number = $ir->number + 1;
       $ir->save();

       // Save Handsontable
       foreach($hot as $l){
         if($l['1']!=''&&isset($l['2'])!=''){
            $dom = new AccAccountedAutoDetail();
            $dom->accounted_auto = $data->id;
            $dom->accounted_fast = $l['1'];
            $dom->description = $l['2'];
            $dom->debit = $l['3'];
            $dom->credit = $l['4'];
            $dom->subject_debit = $l['5'];
            $dom->subject_credit = $l['6'];
            $dom->department = $l['7'];
            $dom->bank_account = $l['8'];
            $dom->cost_code = $l['9'];
            $dom->case_code = $l['10'];
            $dom->statistical_code = $l['11'];
            $dom->work_code = $l['12'];
            $dom->active = 1;
            $dom->save();
         }
       }
       ///////////////////

       // Lưu lịch sử
       $h = new AccHistoryAction();
       $h ->create([
         'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
         'user' => Auth::id(),
         'menu' => $this->menu->id,
         'url'  => $this->url,
         'dataz' => \json_encode($data)]);


         // Lấy lại giá trị hot
         $hot_add = AccAccountedAutoDetail::get_accounted_auto($data->id);
         $arr->accounted_auto_detail = $hot_add;

       // Lấy ID và và phân loại Thêm
       $arr->id = $data->id;
       $arr->t = $type;
       DB::connection(env('CONNECTION_DB_ACC'))->commit();
       broadcast(new \App\Events\DataSend($arr));
       return response()->json(['status'=>true,'message'=> trans('messages.add_success')]);
     }else if($permission['e'] == true && $arr->id){
       $type = 3;
       $data = AccAccountedAuto::find($arr->id);
       // Lưu lịch sử
       $h = new AccHistoryAction();
       $h ->create([
         'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
         'user' => Auth::id(),
         'menu' => $this->menu->id,
         'url'  => $this->url,
         'dataz' => \json_encode($data)]);
      //
      $data->profession = $arr->profession;
      $data->code = $arr->code;
      $data->name = $arr->name;
      $data->name_en = $arr->name_en;
      $data->description = $arr->description;
      $data->active = $arr->active;
      $data->save();


      $dom_all = AccAccountedAutoDetail::get_accounted_auto($data->id);
      //return dd($hot);

      // Save Handsontable
      foreach($hot as $l){
        if($l['1']!=null&&isset($l['2'])!=null){
          if($l['0'] == null){
            $dom = new AccAccountedAutoDetail();
            $dom->active = 1;
          }else{
            $dom = AccAccountedAutoDetail::find($l['0']);
            $dom_all = $dom_all->filter(function ($item) use ($l) {
               return $item->id != $l['0'];
           });
          }
            $dom->accounted_auto = $data->id;
            $dom->accounted_fast = $l['1'];
            $dom->description = $l['2'];
            $dom->debit = $l['3'];
            $dom->credit = $l['4'];
            $dom->subject_debit = $l['5'];
            $dom->subject_credit = $l['6'];
            $dom->department = $l['7'];
            $dom->bank_account = $l['8'];
            $dom->cost_code = $l['9'];
            $dom->case_code = $l['10'];
            $dom->statistical_code = $l['11'];
            $dom->work_code = $l['12'];
            $dom->save();
        }
      }
      // Xóa các dòng
      if($dom_all->count()>0){
        $id_destroy = $dom_all->pluck('id');
        AccAccountedAutoDetail::destroy($id_destroy);
      }
      ///////////////////

       // Lấy lại giá trị hot
       $hot_add = AccAccountedAutoDetail::get_accounted_auto($data->id);
       $arr->accounted_auto_detail = $hot_add;

       // Phân loại Sửa
       $arr->t = $type;
       DB::connection(env('CONNECTION_DB_ACC'))->commit();
       broadcast(new \App\Events\DataSend($arr));
       return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
       }else{
        return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission')]);
       }
      }else{
        return response()->json(['status'=>false,'message'=> trans('messages.code_is_already')]);
      }
     }else{
       DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
       return response()->json(['status'=>false,'error'=>$validator->getMessageBag()->toArray() ,'message'=>trans('messages.error')]);
     }
    }catch(Exception $e){
      DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
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

 public function delete(Request $request) {
   $type = 4;
      try{
        DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
        $permission = $request->session()->get('per');
        $arr = json_decode($request->data);
        if($arr){
          if($permission['d'] == true){
            $data = AccAccountedAuto::get_id_with_detail($arr->id);
            // Lưu lịch sử
            $dom_all = AccAccountedAutoDetail::get_accounted_auto($data->id);
            // Xóa các dòng
            if($dom_all->count()>0){
              $id_destroy = $dom_all->pluck('id');
              AccAccountedAutoDetail::destroy($id_destroy);
            }
            ///////////////////

            $h = new AccHistoryAction();
            $h ->create([
            'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
            'user' => Auth::id(),
            'menu' => $this->menu->id,
            'url'  => $this->url,
            'dataz' => \json_encode($data)]);
            //
            $data->delete();
            DB::connection(env('CONNECTION_DB_ACC'))->commit();
            broadcast(new \App\Events\DataSend($arr));
            return response()->json(['status'=>true,'message'=> trans('messages.delete_success')]);
          }else{
            return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission_delete')]);
          }
       }else{
         return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
       }
      }catch(Exception $e){
        DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
        // Lưu lỗi
        $err = new Error();
        $err ->create([
          'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
          'user_id' => Auth::id(),
          'menu_id' => $this->menu->id,
          'error' => $e->getMessage().' - Line '.$e->getLine(),
          'url'  => $this->url,
          'check' => 0 ]);
        return response()->json(['status'=>false,'message'=> trans('messages.delete_fail').' '.$e->getMessage().' - Line '.$e->getLine()]);
      }
 }

 public function DownloadExcel(){
   return Storage::download('public/downloadFile/'.$this->download);
 }

 public function import(Request $request) {
  $type = 5;
   try{
    DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
   $permission = $request->session()->get('per');
   if($permission['a'] && $request->hasFile('file')){
    if($request->file->getClientOriginalName() == $this->download){
     //Check
     $request->validate([
         'file' => 'required|mimeTypes:'.
               'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,'.
               'application/vnd.ms-excel',
     ]);
       $rs = json_decode($request->data);

       $file = $request->file;
       // Import dữ liệu
       $import = new AccAccountedAutoImport;
       Excel::import($import, $file);
       // Lấy lại dữ liệu
     
       $merged = collect($rs)->push($import->getData());
       //dump($merged);
     // Lưu lịch sử
     $h = new AccHistoryAction();
     $h ->create([
       'type' => $type, // Add : 2 , Edit : 3 , Delete : 4, Import : 5
       'user' => Auth::id(),
       'menu' => $this->menu->id,
       'url'  => $this->url,
       'dataz' => \json_encode($merged)]);
     //
     //Storage::delete($savePath.$filename);
     DB::connection(env('CONNECTION_DB_ACC'))->commit();
     broadcast(new \App\Events\DataSendCollection($merged));
     return response()->json(['status'=>true,'message'=> trans('messages.success_import')]);
    }else{
     return response()->json(['status'=>false,'message'=> trans('messages.incorrect_file')]);
    }    
     }else{
       return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
     }
   }catch(Exception $e){
    DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
     // Lưu lỗi
     $err = new Error();
     $err ->create([
       'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
       'user_id' => Auth::id(),
       'menu_id' => $this->menu->id,
       'error' => $e->getMessage().' - Line '.$e->getLine(),
       'url'  => $this->url,
       'check' => 0 ]);
     return response()->json(['status'=>false,'message'=> trans('messages.failed_import').' '.$e->getMessage().' - Line '.$e->getLine()]);
   }
 }

 public function export(Request $request) {
   $type = 6;
   try{
       $arr = $request->data;
       $page = $request->page;
       //return (new HistoryActionExport($arr))->download('HistoryActionExportErmis.xlsx');
       //$myFile = Excel::download(new HistoryActionExport($arr), 'HistoryActionExportErmis.xlsx');
       $myFile = Excel::raw(new AccAccountedAutoExport($arr,$page), \Maatwebsite\Excel\Excel::XLSX);
       $response =  array(
         'status' =>true,
         'name' => "AccAccountedAutoExportErmis", //no extention needed
         'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($myFile) //mime type of used format
      );
      return response()->json($response);
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
     return response()->json(['status'=>false,'message'=> trans('messages.failed_export').' '.$e->getMessage().' - Line '.$e->getLine()]);
   }
 }

}
