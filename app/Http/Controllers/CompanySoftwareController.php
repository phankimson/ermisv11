<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\HistoryAction;
use App\Http\Model\Menu;
use App\Http\Model\User;
use App\Http\Model\CompanySoftware;
use App\Http\Model\Company;
use App\Http\Model\Software;
use App\Http\Model\License;
use App\Http\Model\Error;
use App\Http\Resources\DropDownListResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Model\Imports\CompanySoftwareImport;
use App\Http\Model\Exports\CompanySoftwareExport;
use App\Classes\Convert;
use App\Classes\SchemaDB;
use Excel;
use Hashids\Hashids;

class CompanySoftwareController extends Controller
{
    public function __construct(Request $request)
   {
       $this->url = $request->segment(3);
       $this->key = "company-software";
       $this->menu = Menu::where('code', '=', $this->key)->first();
       $this->length_hash = 50;
   }

   public function show(){
      $type = Software::get_url("acc");
      $data = CompanySoftware::get_raw_type($type->id);
      $company = collect(DropDownListResource::collection(Company::active()->get()));
      $software = collect(DropDownListResource::collection(Software::active()->get()));
      $license = License::active()->get();
      return view('manage.company_software',['data' => $data, 'company'=>$company, 'software'=>$software, 'license'=>$license, 'key' => $this->key , 'type' => $type->id]);
   }
    public function get(Request $request){
      $type = 9;
      try{
        $req = $request->data;
        $data = CompanySoftware::get_raw_type($req);
        return response()->json(['status'=>true,'data'=> $data ]);
      }catch(Exception $e){
        // Lưu lỗi
        $err = new Error();
        $err ->create([
          'type' => $type, // Add : 2 , Edit : 3 , Delete : 4 , Import : 5 , Export : 6, Timeline : 7 , Loadmore : 8, Load : 9
          'user_id' => Auth::id(),
          'menu_id' => $this->menu->id,
          'error' => $e->getMessage(),
          'url' => $this->url,
          'check' => 0 ]);
        return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
      }
   }

   public function save(Request $request){
     $type = 0;
     try{
   $permission = $request->session()->get('per');
   $arr = json_decode($request->data);
   $validator = Validator::make(collect($arr)->toArray(),[
             'company_id' => 'required'
         ]);
      if($validator->passes()){
        $hashids = new Hashids('',$this->length_hash);
      if($permission['a'] == true && !$arr->id ){
        $type = 2;
        // Check & Tạo database
        $db = CompanySoftware::check_company_software($arr->company_id,$arr->type);
        if($db == 0){
          // Tìm database mẫu
          $db_temp = Software::find($arr->type);
          if($db_temp){
            $db_check = SchemaDB::checkDB($arr->database);
            if ($db_check == 0) {
                SchemaDB::createDB($arr->database);
                $tables = SchemaDB::getAllTable($db_temp->database_temp);
                $name = "Tables_in_".$db_temp->database_temp;
                foreach($tables as $table)
                  {
                    if ($table == 'migrations') {
                        continue;
                    }
                    SchemaDB::copyTableDB($db_temp->database_temp,$arr->database,$table->{$name});
                  }
            }
              $data = new CompanySoftware();
              $data->company_id = $arr->company_id;
              $data->software_id = $arr->type;
              $data->license_id = $arr->license_id;
              $data->free = Convert::intDefaultformat($arr->free);
              $data->database = $arr->database;
              $data->username = $arr->username;
              $data->password = $hashids->encode($arr->password);
              $data->active = $arr->active;
              $data->save();
              // Lưu lịch sử ---- NOT EDIT
              $h = new HistoryAction();
              $h ->create([
                'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
                'user' => Auth::id(),
                'menu' => $this->menu->id,
                'url' => $this->url,
                'dataz' => \json_encode($data)]);
              /////////////////////////////
              // Lấy ID và và phân loại Thêm
              $arr->id = $data->id;
              $arr->t = $type;
              //Lưu lại pass
              $arr->password = $data->password;

              broadcast(new \App\Events\DataSend($arr));
              return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
          }else{
              return response()->json(['status'=>false,'message'=> trans('messages.no_database_found')]);
          }
        }else{
              return response()->json(['status'=>false,'message'=> trans('messages.the_company_has_registered_this_software')]);
        }
      }else if($permission['e'] == true && $arr->id){
        $type = 3;
        $data = CompanySoftware::find($arr->id);
        // Lưu lịch sử ---- NOT EDIT
        $h = new HistoryAction();
        $h ->create([
          'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
          'user' => Auth::id(),
          'menu' => $this->menu->id,
          'url' => $this->url,
          'dataz' => \json_encode($data)]);
        /////////////////////////////
        $old_password = $data->password;
        $data->company_id = $arr->company_id;
        $data->software_id = $arr->type;
        $data->license_id = $arr->license_id;
        $data->free = $arr->free;
        $data->database = $arr->database;
        $data->username = $arr->username;
        $password_new = $hashids->encode($arr->password);
        if ($password_new !=  $old_password) {
              $data->password = $password_new;
         }
        $data->active = $arr->active;
        $data->save();
        //Lưu lại pass
        $arr->password = $data->password;
        // Phân loại Sửa
        $arr->t = $type;
        broadcast(new \App\Events\DataSend($arr));
        return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
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
         'url' => $this->url,
         'check' => 0 ]);
       return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
     }
  }

  public function delete(Request $request) {
    $type = 4;
       try{
         $permission = $request->session()->get('per');
         $arr = json_decode($request->data);
         if($arr){
           if($permission['d'] == true){
             $data = CompanySoftware::find($arr->id);
             // Lưu lịch sử
             $h = new HistoryAction();
             $h ->create([
               'type' => 4, // Add : 2 , Edit : 3 , Delete : 4
               'user' => Auth::id(),
               'menu' => $this->menu->id,
               'url' => $this->url,
               'dataz' => \json_encode($data)]);
             //
             $data->delete();
             broadcast(new \App\Events\DataSend($arr));
             return response()->json(['status'=>true,'message'=> trans('messages.delete_success')]);
           }else{
             return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission_delete')]);
           }
        }else{
          return response()->json(['status'=>false,'message'=> trans('messages.no_data')]);
        }
       }catch(Exception $e){
         // Lưu lỗi
         $err = new Error();
         $err ->create([
           'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
           'user_id' => Auth::id(),
           'menu_id' => $this->menu->id,
           'error' => $e->getMessage(),
           'url' => $this->url,
           'check' => 0 ]);
         return response()->json(['status'=>false,'message'=> trans('messages.delete_fail').' '.$e->getMessage()]);
       }
  }
  public function DownloadExcel(Request $request){
    return Storage::download('public/downloadFile/CompanySoftware.xlsx');
  }

  public function import(Request $request) {
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
        Excel::import(new CompanySoftwareImport, $file);
        // Lấy lại dữ liệu
        $array = CompanySoftware::get_raw_type($rs->ts);

        // Import dữ liệu bằng collection
        $results = Excel::toCollection(new CompanySoftwareImport, $file);
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
      $h = new HistoryAction();
      $h ->create([
        'type' => $type, // Add : 2 , Edit : 3 , Delete : 4, Import : 5
        'user' => Auth::id(),
        'menu' => $this->menu->id,
        'url' => $this->url,
        'dataz' => \json_encode($results)]);
      //
      //Storage::delete($savePath.$filename);
      broadcast(new \App\Events\DataSendCollection($merged));
      return response()->json(['status'=>true,'message'=> trans('messages.success_import')]);
      }else{
        return response()->json(['status'=>false,'message'=> trans('messages.no_data')]);
      }
    }catch(Exception $e){
      // Lưu lỗi
      $err = new Error();
      $err ->create([
        'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
        'user_id' => Auth::id(),
        'menu_id' => $this->menu->id,
        'error' => $e->getMessage(),
        'url' => $this->url,
        'check' => 0 ]);
      return response()->json(['status'=>false,'message'=> trans('messages.failed_import').' '.$e->getMessage()]);
    }
  }

  public function export(Request $request) {
    $type = 6;
    try{
        $arr = $request->data;
        //return (new HistoryActionExport($arr))->download('HistoryActionExportErmis.xlsx');
        //$myFile = Excel::download(new HistoryActionExport($arr), 'HistoryActionExportErmis.xlsx');
        $myFile = Excel::raw(new CompanySoftwareExport($arr), \Maatwebsite\Excel\Excel::XLSX);
        $response =  array(
          'status' =>true,
          'name' => "CompanySoftwareExportErmis", //no extention needed
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
        'url' => $this->url,
        'check' => 0 ]);
      return response()->json(['status'=>false,'message'=> trans('messages.failed_import').' '.$e->getMessage()]);
    }
  }

}
