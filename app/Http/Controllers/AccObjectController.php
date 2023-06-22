<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\Menu;
use App\Http\Model\AccObject;
use App\Http\Model\AccObjectType;
use App\Http\Model\AccObjectGroup;
use App\Http\Model\AccNumberCode;
use App\Http\Model\Regions;
use App\Http\Model\Area;
use App\Http\Model\Distric;
use App\Http\Model\AccDepartment;
use App\Http\Model\Country;
use App\Http\Model\CompanySoftware;
use App\Http\Model\Company;
use App\Http\Model\Error;
use App\Http\Resources\DropDownListResource;
use App\Http\Resources\ObjectTypeDropDownListResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Model\Imports\AccObjectImport;
use App\Http\Model\Exports\AccObjectExport;
use App\Classes\Convert;
use App\Http\Model\AccObjectFilterObjectType;
use Excel;

class AccObjectController extends Controller
{
  public function __construct(Request $request)
  {
     $this->url =  $request->segment(3);
     $this->key = "object";
     $this->menu = Menu::where('code', '=', $this->key)->first();
 }

  public function show(Request $request){
    $type = 2;
    $mysql2 = $request->session()->get('mysql2');
    config(['database.connections.mysql2' => $mysql2]);
    $data = AccObject::get_raw();
    $object_type = collect(ObjectTypeDropDownListResource::collection(AccObjectType::active()->OrderBy('code','asc')->get()));
    $object_group = collect(DropDownListResource::collection(AccObjectGroup::active()->OrderBy('code','asc')->get()));
    $menu = Menu::get_raw_type($type);
    $regions = collect(DropDownListResource::collection(Regions::active()->OrderBy('code','asc')->get()));
    $area = collect(DropDownListResource::collection(Area::active()->OrderBy('code','asc')->get()));
    $department = collect(DropDownListResource::collection(AccDepartment::active()->OrderBy('code','asc')->get()));
    $distric = collect(DropDownListResource::collection(Distric::active()->OrderBy('code','asc')->get()));
    $country = Country::all();
    return view('acc.object',['data' => $data, 'key' => $this->key ,'department'=>$department,'object_type' =>$object_type,'object_group' =>$object_group,'menu'=>$menu,'regions'=>$regions , 'area'=>$area , 'distric'=>$distric , 'country'=>$country]);
  }

  public function load(Request $request){
    $type = 10;
    try{
    $req = json_decode($request->data);
    $mysql2 = $request->session()->get('mysql2');
    config(['database.connections.mysql2' => $mysql2]);
    $object_type = AccObjectType::find($req);  
    $data = AccNumberCode::get_code($this->key.'_'.$object_type->filter);
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
        'error' => $e->getMessage(),
        'url'  => $this->url,
        'check' => 0 ]);
      return response()->json(['status'=>false,'message'=> trans('messages.error').' '.$e->getMessage()]);
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
      $request->session()->put('mysql2', $params);
      config(['database.connections.mysql2' => $params]);
      $data = AccObject::get_raw();
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
            'object_type' => 'required',
        ]);
     if($validator->passes()){
      $code_check = AccObject::WhereCheck('code',$arr->code,'id',$arr->id)->first();
      if($code_check == null){
     if($permission['a'] == true && !$arr->id ){
       $type = 2;
       $data = new AccObject();
       $data->object_group = $arr->object_group;
       $data->code = $arr->code;
       $data->name = $arr->name;
       $data->name_1 = $arr->name_1;
       $data->identity_card = $arr->identity_card;
       $data->issued_by_identity_card = $arr->issued_by_identity_card;
       $data->date_identity_card = $arr->date_identity_card;
       $data->address = $arr->address;
       $data->email = $arr->email;
       $data->tax_code = $arr->tax_code;
       $data->invoice_form = $arr->invoice_form;
       $data->invoice_symbol = $arr->invoice_symbol;
       $data->director = $arr->director;
       $data->phone = $arr->phone;
       $data->fax = $arr->fax;
       $data->full_name_contact = $arr->full_name_contact;
       $data->address_contact = $arr->address_contact;
       $data->title_contact = $arr->title_contact;
       $data->email_contact = $arr->email_contact;
       $data->telephone1_contact = $arr->telephone1_contact;
       $data->telephone2_contact = $arr->telephone2_contact;
       $data->department = $arr->department;
       $data->bank_name = $arr->bank_name;
       $data->bank_branch = $arr->bank_branch;
       $data->bank_account = $arr->bank_account;
       $data->country = $arr->country;
       $data->regions = $arr->regions;
       $data->area = $arr->area;
       $data->distric = $arr->distric;
       $data->marketing = $arr->marketing;
       $data->company_size = $arr->company_size;
       $data->level = $arr->level;
       $data->active = $arr->active;
       $data->save();

       // Lưu Object Filter Object type    
       foreach($arr->object_type as $t){
        $ot = new AccObjectFilterObjectType();
        $ot->object  = $data->id;
        $ot->object_type = $t;
        $ot->save();
       }

       // Lưu mã code tự tăng

       $p = AccObjectType::find($arr->object_type[0]);
         $ir = AccNumberCode::get_code($this->key.'_'.$p->filter);
         $ir->number = $ir->number + 1;
         $ir->save();
       
       //if (strpos($arr->object_type, ',') == true){
       //  $p = explode(",", $p);
       //  $ir = AccNumberCode::get_code($this->key.'_'.$p[0]);
       // $ir->number = $ir->number + 1;
       //  $ir->save();
       // }else{
       //  $ir = AccNumberCode::get_code($this->key.'_'.$arr->object_type);
       //  $ir->number = $ir->number + 1;
       //  $ir->save();
       //}


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
     }else if($permission['e'] == true && $arr->id){
       $type = 3;
       $data = AccObject::find($arr->id);
       // Lưu lịch sử
       $h = new AccHistoryAction();
       $h ->create([
         'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
         'user' => Auth::id(),
         'menu' => $this->menu->id,
         'url'  => $this->url,
         'dataz' => \json_encode($data)]);
      //

      $data->object_group = $arr->object_group;
      $data->code = $arr->code;
      $data->name = $arr->name;
      $data->name_1 = $arr->name_1;
      $data->identity_card = $arr->identity_card;
      $data->issued_by_identity_card = $arr->issued_by_identity_card;
      $data->date_identity_card = $arr->date_identity_card;
      $data->address = $arr->address;
      $data->email = $arr->email;
      $data->tax_code = $arr->tax_code;
      $data->invoice_form = $arr->invoice_form;
      $data->invoice_symbol = $arr->invoice_symbol;
      $data->director = $arr->director;
      $data->phone = $arr->phone;
      $data->fax = $arr->fax;
      $data->full_name_contact = $arr->full_name_contact;
      $data->address_contact = $arr->address_contact;
      $data->title_contact = $arr->title_contact;
      $data->email_contact = $arr->email_contact;
      $data->telephone1_contact = $arr->telephone1_contact;
      $data->telephone2_contact = $arr->telephone2_contact;
      $data->department = $arr->department;
      $data->bank_name = $arr->bank_name;
      $data->bank_branch = $arr->bank_branch;
      $data->bank_account = $arr->bank_account;
      $data->country = $arr->country;
      $data->regions = $arr->regions;
      $data->area = $arr->area;
      $data->distric = $arr->distric;
      $data->marketing = $arr->marketing;
      $data->company_size = $arr->company_size;
      $data->level = $arr->level;
      $data->active = $arr->active;
      $data->save();

       // Lưu Object Filter Object type 
       $ob_all = AccObjectFilterObjectType::get_object($data->id);
       foreach($arr->object_type as $t){
        $obc = AccObjectFilterObjectType::get_item($data->id,$t);
        if($obc == null){
          $ot = new AccObjectFilterObjectType();
          $ot->object  = $data->id;
          $ot->object_type = $t;
          $ot->save();
        }else{
            $ob_all = $ob_all->filter(function ($item) use ($t) {
               return $item->object_type != $t;
           });
          }
       }

       // Xoa Object Filter Object type 
       if($ob_all->count()>0){
        $id_destroy = $ob_all->pluck('id');
        AccObjectFilterObjectType::destroy($id_destroy);
      }

       // Phân loại Sửa
       $arr->t = $type;
       broadcast(new \App\Events\DataSend($arr));
       return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
       }else{
        return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission')]);
       }
      }else{
        return response()->json(['status'=>false,'message'=> trans('messages.code_is_already')]);
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
            $data = AccObject::find($arr->id);
            // Lưu lịch sử
            $h = new AccHistoryAction();
            $h ->create([
            'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
            'user' => Auth::id(),
            'menu' => $this->menu->id,
            'url'  => $this->url,
            'dataz' => \json_encode($data)]);
            //
            $ob_all = AccObjectFilterObjectType::get_object($arr->id);           
           // Xoa Object Filter Object type 
              if($ob_all->count()>0){
                $id_destroy = $ob_all->pluck('id');
                AccObjectFilterObjectType::destroy($id_destroy);
              }
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
   return Storage::download('public/downloadFile/AccObject.xlsx');
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
       Excel::import(new AccObjectImport, $file);
       // Lấy lại dữ liệu
       $array = AccObject::get_raw();

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
       $myFile = Excel::raw(new AccObjectExport($arr), \Maatwebsite\Excel\Excel::XLSX);
       $response =  array(
         'status' =>true,
         'name' => "AccObjectExportErmis", //no extention needed
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
