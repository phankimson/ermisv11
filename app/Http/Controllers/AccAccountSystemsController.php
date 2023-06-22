<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\Menu;
use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccAccountType;
use App\Http\Model\AccAccountNature;
use App\Http\Model\CompanySoftware;
use App\Http\Model\Company;
use App\Http\Model\Document;
use App\Http\Model\DocumentType;
use App\Http\Resources\DropDownListResource;
use App\Http\Model\Error;
use Illuminate\Support\Facades\Storage;
use App\Http\Model\Imports\AccAccountSystemsImport;
use App\Http\Model\Exports\AccAccountSystemsExport;
use App\Classes\Convert;
use Excel;

class AccAccountSystemsController extends Controller
{
  public function __construct(Request $request)
  {
     $this->url =  $request->segment(3);
     $this->key = "account-systems";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->code_type = 'ACC_SYSTEM';
 }

  public function show(Request $request){
    $mysql2 = $request->session()->get('mysql2');
    config(['database.connections.mysql2' => $mysql2]);
    $type_account = collect(DropDownListResource::collection(AccAccountType::active()->OrderBy('code','asc')->get()));
    $nature = collect(DropDownListResource::collection(AccAccountNature::active()->OrderBy('code','asc')->get()));
    $document_type = DocumentType::get_code($this->code_type);
    $document = collect(DropDownListResource::collection(Document::get_type($document_type->id)));
    $data = AccAccountSystems::get_raw();
    return view('acc.account_systems',['data' => $data, 'key' => $this->key , 'parent'=>$data , 'type_account'=>$type_account,'nature' => $nature,'document'=>$document ]);
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
      $request->session()->put('mysql2', $params);
      config(['database.connections.mysql2' => $params]);
      $data = AccAccountSystems::get_raw();
      return response()->json(['status'=>true,'data'=> $data,'com_name'=> $com->name ]);
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

  public function save(Request $request){
    $mysql2 = $request->session()->get('mysql2');
    config(['database.connections.mysql2' => $mysql2]);
    $type = 0;
    try{
  $permission = $request->session()->get('per');
  $arr = json_decode($request->data);
  $validator = Validator::make(collect($arr)->toArray(),[
            'code' => ['required','max:50'],
            'name' => 'required',
        ]);
     if($validator->passes()){
     if($permission['a'] == true && !$arr->id ){
       $check_code = AccAccountSystems::get_code($arr->code);
       if(!$check_code){
         $type = 2;
         $data = new AccAccountSystems();
         $data->type = $arr->type;
         $data->code = $arr->code;
         $data->name = $arr->name;
         $data->name_en = $arr->name_en;
         $data->parent_id = Convert::StringDefaultformatNull($arr->parent_id);
         $data->nature = $arr->nature;
         $data->date_start = Convert::dateDefaultNull($arr->date_start);
         $data->date_end = Convert::dateDefaultNull($arr->date_end);
         $data->description = $arr->description;
         $data->detail_object = $arr->detail_object;
         $data->detail_bank_account = $arr->detail_bank_account;
         $data->detail_work = $arr->detail_work;
         $data->detail_cost = $arr->detail_cost;
         $data->detail_case = $arr->detail_case;
         $data->detail_statistical = $arr->detail_statistical;
         $data->detail_orders = $arr->detail_orders;
         $data->detail_contract = $arr->detail_contract;
         $data->detail_depreciation = $arr->detail_depreciation;
         $data->detail_attribution = $arr->detail_attribution;
         $data->document_id = $arr->document_id;
         $data->active = $arr->active;
         $data->save();

         // Lưu lịch sử
         $h = new AccHistoryAction();
         $h ->create([
           'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
           'user' => Auth::id(),
           'menu' => $this->menu->id,
           'url'  => $this->url,
           'dataz' => \json_encode($data)]);

         // Lấy ID và và phân loại Thêm
         $arr->id = $data->id;
         $arr->t = $type;
         broadcast(new \App\Events\DataSend($arr));
         return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
       }else{
         return response()->json(['status'=>false,'message'=> trans('messages.account_is_already')]);
       }
     }else if($permission['e'] == true && $arr->id){
       $check_code = AccAccountSystems::get_code_not_id($arr->code,$arr->id);
       if($check_code->count() == 0){
         $type = 3;
         $data = AccAccountSystems::find($arr->id);
         // Lưu lịch sử
         $h = new AccHistoryAction();
         $h ->create([
           'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
           'user' => Auth::id(),
           'menu' => $this->menu->id,
           'url'  => $this->url,
           'dataz' => \json_encode($data)]);
        //

        $data->type = $arr->type;
        $data->code = $arr->code;
        $data->name = $arr->name;
        $data->name_en = $arr->name_en;
        $data->parent_id = Convert::StringDefaultformatNull($arr->parent_id);
        $data->nature = $arr->nature;
        $data->date_start = Convert::dateDefaultNull($arr->date_start);
        $data->date_end = Convert::dateDefaultNull($arr->date_end);
        $data->description = $arr->description;
        $data->detail_object = $arr->detail_object;
        $data->detail_bank_account = $arr->detail_bank_account;
        $data->detail_work = $arr->detail_work;
        $data->detail_cost = $arr->detail_cost;
        $data->detail_case = $arr->detail_case;
        $data->detail_statistical = $arr->detail_statistical;
        $data->detail_orders = $arr->detail_orders;
        $data->detail_contract = $arr->detail_contract;
        $data->detail_depreciation = $arr->detail_depreciation;
        $data->detail_attribution = $arr->detail_attribution;
        $data->document_id = $arr->document_id;
        $data->active = $arr->active;
        $data->save();
         // Phân loại Sửa
         $arr->t = $type;
         broadcast(new \App\Events\DataSend($arr));
         return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
       }else{
         return response()->json(['status'=>false,'message'=> trans('messages.account_is_already')]);
       }

       }else{
        return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission')]);
       }
     }else{
       return response()->json(['status'=>false,'error'=>$validator->getMessageBag()->toArray() ,'message'=>trans('messages.error')]);
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
          if($permission['d'] == true){
            $data = AccAccountSystems::find($arr->id);
            $child = AccAccountSystems::get_child($data->id);
            $child->each(function ($item) {
              $item->parent_id = null;
              $item->save();
            });
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
            broadcast(new \App\Events\DataSend($arr));
            return response()->json(['status'=>true,'message'=> trans('messages.delete_success')]);
          }else{
            return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission_delete')]);
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

 public function DownloadExcel(Request $request){
   return Storage::download('public/downloadFile/AccAccountSystems.xlsx');
 }

 public function import(Request $request) {
   ini_set('max_execution_time', 600);
   $mysql2 = $request->session()->get('mysql2');
   config(['database.connections.mysql2' => $mysql2]);
  $type = 5;
   try{
   $permission = $request->session()->get('per');
   if($permission['a'] && $request->hasFile('file')){
     //Check
     $request->validate([
         'file' => 'required|mimeTypes:'.
               'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,'.
               'application/vnd.ms-excel',
     ]);
       $rs = json_decode($request->data);

       $file = $request->file;
       // Import dữ liệu
       Excel::import(new AccAccountSystemsImport, $file);
       // Lấy lại dữ liệu
       $array = AccAccountSystems::get_raw();

       // Import dữ liệu bằng collection
       //$results = Excel::toCollection(new HistoryActionImport, $file);
       //dump($results);
       //foreach($results[0] as $item){
       //  $data = new HistoryAction();
       //  $data->type = $item->get('type');
       //  $data->user = $item->get('user');
       //  $data->menu = $item->get('menu');
       //  $data->dataz = $item->get('dataz');
       //  $data->save();
       //  $arr->push($data);
       //}
       $merged = collect($rs)->push($array);
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
     broadcast(new \App\Events\DataSendCollection($merged));
     return response()->json(['status'=>true,'message'=> trans('messages.success_import')]);
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
     return response()->json(['status'=>false,'message'=> trans('messages.failed_import').' '.$e->getMessage()]);
   }
 }

 public function export(Request $request) {
   $mysql2 = $request->session()->get('mysql2');
   config(['database.connections.mysql2' => $mysql2]);
   $type = 6;
   try{
       $arr = $request->data;
       //return (new HistoryActionExport($arr))->download('HistoryActionExportErmis.xlsx');
       //$myFile = Excel::download(new HistoryActionExport($arr), 'HistoryActionExportErmis.xlsx');
       $myFile = Excel::raw(new AccAccountSystemsExport($arr), \Maatwebsite\Excel\Excel::XLSX);
       $response =  array(
         'status' =>true,
         'name' => "AccAccountSystemsExportErmis", //no extention needed
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
       'error' => $e->getMessage(),
       'url'  => $this->url,
       'check' => 0 ]);
     return response()->json(['status'=>false,'message'=> trans('messages.failed_import').' '.$e->getMessage()]);
   }
 }

}
