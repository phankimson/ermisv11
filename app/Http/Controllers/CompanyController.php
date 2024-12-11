<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\HistoryAction;
use App\Http\Model\Menu;
use App\Http\Model\Company;
use App\Http\Model\Regions;
use App\Http\Model\Area;
use App\Http\Model\Distric;
use App\Http\Model\Country;
use App\Http\Model\Error;
use App\Http\Model\Systems;
use App\Http\Resources\DropDownListResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Model\Imports\CompanyImport;
use App\Http\Model\Exports\CompanyExport;
use App\Classes\Convert;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
  protected $url;
  protected $key;
  protected $menu;
  protected $page_system;
  public function __construct(Request $request)
 {
     $this->url = $request->segment(3);
     $this->key = "company";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->page_system = "MAX_COUNT_CHANGE_PAGE";
 }

  public function show(){
    //$data = Company::get_raw();
    $regions = collect(DropDownListResource::collection(Regions::active()->get()));
    $area = collect(DropDownListResource::collection(Area::active()->get()));
    $distric = collect(DropDownListResource::collection(Distric::active()->get()));
    $country = Country::all();
    $count = Company::count();
    $sys_page = Systems::get_systems($this->page_system);
    $paging = $count>$sys_page->value?1:0; 
    return view('manage.company',['paging' => $paging, 'key' => $this->key , 'regions'=>$regions , 'area'=>$area , 'distric'=>$distric , 'country'=>$country]);
  }


  
  public function data(Request $request){    
    $total = Company::count();
    $sys_page = Systems::get_systems($this->page_system);    
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
          $arr = Company::get_raw_skip_filter_page($skip,$perPage,$orderby,$asc,$filter_sql);
          $total = Company::whereRaw($filter_sql)->count();
        }else{
          $arr = Company::get_raw_skip_page($skip,$perPage,$orderby,$asc);   
        }   
    $data = collect(['data' => $arr,'total' => $total]);            
    if($data){
      return response()->json($data);
    }else{
      return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
    }
  }

  public function save(Request $request){
    $type = 0 ;
    try{
      DB::beginTransaction();
    $permission = $request->session()->get('per');
    $arr = json_decode($request->data);
    $validator = Validator::make(collect($arr)->toArray(),[
        'code' => ['required','max:50'],
        'name' => 'required',
          ]);
        if($validator->passes()){
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
       DB::commit();
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
       DB::commit();
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
    }else{
      DB::rollBack();
       return response()->json(['status'=>false,'error'=>$validator->getMessageBag()->toArray() ,'message'=>trans('messages.error')]);
     }
    }catch(Exception $e){
      DB::rollBack();
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
        DB::beginTransaction();
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
            DB::commit();
            broadcast(new \App\Events\DataSend($arr));
            return response()->json(['status'=>true,'message'=> trans('messages.delete_success')]);
          }else{
            return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission_delete')]);
          }
       }else{
         return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
       }
      }catch(Exception $e){
        DB::rollBack();
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

 public function DownloadExcel(){
   return Storage::download('public/downloadFile/Company.xlsx');
 }

 public function import(Request $request) {
   $type = 5;
   try{
    DB::beginTransaction();
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
       $import = new CompanyImport;
       Excel::import($import, $file);
       // Lấy lại dữ liệu      
     
       $merged = collect($rs)->push($import->getData());
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
     DB::commit();
     broadcast(new \App\Events\DataSendCollection($merged));
     return response()->json(['status'=>true,'message'=> trans('messages.success_import')]);
     }else{
       return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
     }
   }catch(Exception $e){
    DB::rollBack();
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
       $page = $request->page;
       //return (new HistoryActionExport($arr))->download('HistoryActionExportErmis.xlsx');
       //$myFile = Excel::download(new HistoryActionExport($arr), 'HistoryActionExportErmis.xlsx');
       $myFile = Excel::raw(new CompanyExport($arr,$page), \Maatwebsite\Excel\Excel::XLSX);
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
     return response()->json(['status'=>false,'message'=> trans('messages.failed_export').' '.$e->getMessage()]);
   }
 }

}
