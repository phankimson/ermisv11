<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\HistoryAction;
use App\Http\Model\User;
use App\Http\Model\Menu;
use App\Http\Model\Company;
use App\Http\Model\Regions;
use App\Http\Model\Area;
use App\Http\Model\Distric;
use App\Http\Model\Country;
use App\Http\Model\Error;
use App\Http\Resources\DropDownListResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Model\Imports\CompanyImport;
use App\Http\Model\Exports\CompanyExport;
use App\Classes\Convert;
use Excel;

class CompanyController extends Controller
{
  public function __construct(Request $request)
 {
     $this->url = $request->segment(3);
     $this->key = "company";
     $this->menu = Menu::where('code', '=', $this->key)->first();
 }

  public function show(){
    $data = Company::get_raw();
    $regions = collect(DropDownListResource::collection(Regions::active()->get()));
    $area = collect(DropDownListResource::collection(Area::active()->get()));
    $distric = collect(DropDownListResource::collection(Distric::active()->get()));
    $country = Country::all();
    return view('manage.company',['data' => $data, 'key' => $this->key , 'regions'=>$regions , 'area'=>$area , 'distric'=>$distric , 'country'=>$country]);
  }

  public function save(Request $request){
    $type = 0 ;
    try{
  $permission = $request->session()->get('per');
  $arr = json_decode($request->data);
    if($arr){
      $code_check = Company::WhereCheck('code',$arr->code,'id',$arr->id)->first();
      if($code_check == null){
     if($permission['a'] == true && !$arr->id ){
       $type = 2;
       $data = new Company();
       $data->code = $arr->code;
       $data->name = $arr->name;
       $data->address = $arr->address;
       $data->email = $arr->email;
       $data->tax_code = $arr->tax_code;
       $data->director = $arr->director;
       $data->phone = $arr->phone;
       $data->fax = $arr->fax;
       $data->full_name_contact = $arr->full_name_contact;
       $data->address_contact = $arr->address_contact;
       $data->title_contact = $arr->title_contact;
       $data->email_contact = $arr->email_contact;
       $data->telephone1_contact = $arr->telephone1_contact;
       $data->telephone2_contact = $arr->telephone2_contact;
       $data->country = $arr->country;
       $data->regions = $arr->regions;
       $data->area = $arr->area;
       $data->distric = $arr->distric;
       $data->marketing = $arr->marketing;
       $data->company_size = $arr->company_size;
       $data->level = Convert::intDefaultformat($arr->level);
       $data->active = $arr->active;
       $data->save();

       // Lưu lịch sử
       $h = new HistoryAction();
       $h ->create([
         'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
         'user' => Auth::id(),
         'menu' => $this->menu->id,
          'url' => $this->url,
         'dataz' => \json_encode($data)]);

       // Lấy ID và và phân loại Thêm
       $arr->id = $data->id;
       $arr->t = $type;
       broadcast(new \App\Events\DataSend($arr));
       return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
     }else if($permission['e'] == true && $arr->id){
       $type = 3;
       $data = Company::find($arr->id);
       // Lưu lịch sử
       $h = new HistoryAction();
       $h ->create([
         'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
         'user' => Auth::id(),
         'menu' => $this->menu->id,
          'url' => $this->url,
         'dataz' => \json_encode($data)]);
      //

      $data->code = $arr->code;
      $data->name = $arr->name;
      $data->address = $arr->address;
      $data->email = $arr->email;
      $data->tax_code = Convert::StringDefaultformat($arr->tax_code);
      $data->director = $arr->director;
      $data->phone = $arr->phone;
      $data->fax = $arr->fax;
      $data->full_name_contact = $arr->full_name_contact;
      $data->address_contact = $arr->address_contact;
      $data->title_contact = $arr->title_contact;
      $data->email_contact = $arr->email_contact;
      $data->telephone1_contact = $arr->telephone1_contact;
      $data->telephone2_contact = $arr->telephone2_contact;
      $data->country = $arr->country;
      $data->regions = $arr->regions;
      $data->area = $arr->area;
      $data->distric = $arr->distric;
      $data->marketing = $arr->marketing;
      $data->company_size = $arr->company_size;
      $data->level = Convert::intDefaultformat($arr->level);
      $data->active = $arr->active;
      $data->save();
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
            $data = Company::find($arr->id);
            // Lưu lịch sử
            $h = new HistoryAction();
            $h ->create([
            'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
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
         return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
       }
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
        return response()->json(['status'=>false,'message'=> trans('messages.delete_fail').' '.$e->getMessage()]);
      }
 }

 public function DownloadExcel(Request $request){
   return Storage::download('public/downloadFile/Company.xlsx');
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
       Excel::import(new CompanyImport, $file);
       // Lấy lại dữ liệu
       $array = Company::get_raw();

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
     $h = new HistoryAction();
     $h ->create([
       'type' => $type, // Add : 2 , Edit : 3 , Delete : 4, Import : 5
       'user' => Auth::id(),
       'menu' => $this->menu->id,
        'url' => $this->url,
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
       $myFile = Excel::raw(new CompanyExport($arr), \Maatwebsite\Excel\Excel::XLSX);
       $response =  array(
         'status' =>true,
         'name' => "CompanyExportErmis", //no extention needed
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
