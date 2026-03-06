<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\Menu;
use App\Http\Model\AccSuppliesGoodsType;
use App\Http\Model\CompanySoftware;
use App\Http\Model\Company;
use App\Http\Model\AccNumberCode;
use App\Http\Model\AccSystems;
use Illuminate\Support\Facades\Storage;
use App\Http\Model\Imports\AccSuppliesGoodsTypeImport;
use App\Http\Model\Exports\AccSuppliesGoodsTypeExport;
use App\Classes\Convert;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\DB;

class AccSuppliesGoodsTypeController extends Controller
{
      protected $url;
      protected $key;
      protected $menu;
      protected $page_system;
      protected $download;
  public function __construct(Request $request)
 {
     $this->url =  $request->segment(3);
     $this->key = "supplies-goods-type";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->page_system = "MAX_COUNT_CHANGE_PAGE";
     $this->download = 'AccSuppliesGoodsType.xlsx';
 }

  public function show(){
    //$data = AccSuppliesGoodsType::get_raw();
    $count = AccSuppliesGoodsType::count();
    $sys_page = AccSystems::get_systems($this->page_system);
    $paging = $count>$sys_page->value?1:0;   
    return view('acc.'.str_replace("-", "_", $this->key),['paging' => $paging, 'key' => $this->key ]);
  }

  
  public function data(Request $request){   
    $total = AccSuppliesGoodsType::count();
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
          $filter_conditions = Convert::parseFilterConditions($filter);
          if($filter_conditions === null){
            return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
          }
          $arr = AccSuppliesGoodsType::get_raw_skip_filter_page($skip,$perPage,$orderby,$asc,$filter_conditions);
          $total = Convert::applyFilterConditions(AccSuppliesGoodsType::query(), $filter_conditions)->count();
        }else{
          $arr = AccSuppliesGoodsType::get_raw_skip_page($skip,$perPage,$orderby,$asc); 
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
      return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
    }
  }

  public function ChangeDatabase(Request $request){
    $type = 9;
    try{
      $req = json_decode($request->data);
      $db = CompanySoftware::find($req->database);
      if(!$db){
        return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
      }
      $com = Company::find($db->company_id);
      if(!$com){
        return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
      }
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
      DB::purge('mysql2');
      DB::reconnect('mysql2');
      $data = AccSuppliesGoodsType::get_raw();
      return response()->json(['status'=>true,'data'=> $data,'com_name'=> $com->name ]);
    }catch(Exception $e){
      return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
    }
 }

  public function save(Request $request){
    $type = 0;
    try{
      DB::connection(env('CONNECTION_DB_ACC'))->beginTransaction();
      $permission = $request->session()->get('per');
      $arr = json_decode($request->data);
      $validator = Validator::make(collect($arr)->toArray(),[
            'code' => ['required','max:50'],
            'name' => 'required',
        ]);
     if($validator->passes()){
      $code_check = AccSuppliesGoodsType::WhereCheck('code',$arr->code,'id',$arr->id)->first();
      if($code_check == null){
     if($permission['a'] == true && !$arr->id ){
       $type = 2;
       $data = new AccSuppliesGoodsType();
       $data->code = $arr->code;
       $data->name = $arr->name;
       $data->name_en = $arr->name_en;
       $data->account_default = $arr->account_default;
       $data->filter = $arr->filter;
       $data->active = $arr->active;
       $data->save();

       // LÃƒâ€ Ã‚Â°u mÃƒÆ’Ã‚Â£ code tÃƒÂ¡Ã‚Â»Ã‚Â± tÃƒâ€žÃ†â€™ng
       $ir = AccNumberCode::get_code($this->key);
       $ir->number = $ir->number + 1;
       $ir->save();


       // LÃƒâ€ Ã‚Â°u lÃƒÂ¡Ã‚Â»Ã¢â‚¬Â¹ch sÃƒÂ¡Ã‚Â»Ã‚Â­
       $h = new AccHistoryAction();
       $h ->create([
         'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
         'user' => Auth::id(),
         'menu' => $this->menu->id,
          'url'  => $this->url,
         'dataz' => \json_encode($data)]);

       // LÃƒÂ¡Ã‚ÂºÃ‚Â¥y ID vÃƒÆ’Ã‚Â  vÃƒÆ’Ã‚Â  phÃƒÆ’Ã‚Â¢n loÃƒÂ¡Ã‚ÂºÃ‚Â¡i ThÃƒÆ’Ã‚Âªm
       $arr->id = $data->id;
       $arr->t = $type;
       DB::connection(env('CONNECTION_DB_ACC'))->commit();
       broadcast(new \App\Events\DataSend($arr));
       return response()->json(['status'=>true,'message'=> trans('messages.add_success')]);
     }else if($permission['e'] == true && $arr->id){
       $type = 3;
       $data = AccSuppliesGoodsType::find($arr->id);
       if(!$data){
          return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
        }
       // LÃƒâ€ Ã‚Â°u lÃƒÂ¡Ã‚Â»Ã¢â‚¬Â¹ch sÃƒÂ¡Ã‚Â»Ã‚Â­
       $h = new AccHistoryAction();
       $h ->create([
         'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
         'user' => Auth::id(),
         'menu' => $this->menu->id,
          'url'  => $this->url,
         'dataz' => \json_encode($data)]);
      //

      $data->code = $arr->code;
      $data->name = $arr->name;
      $data->name_en = $arr->name_en;
      $data->account_default = $arr->account_default;
      $data->filter = $arr->filter;
      $data->active = $arr->active;
      $data->save();
       // PhÃƒÆ’Ã‚Â¢n loÃƒÂ¡Ã‚ÂºÃ‚Â¡i SÃƒÂ¡Ã‚Â»Ã‚Â­a
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
       return response()->json(['status'=>false,'error'=>$validator->getMessageBag()->toArray() ,'message'=>trans('messages.error')]);
     }
    }catch(Exception $e){
      DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
      return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
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
            $data = AccSuppliesGoodsType::find($arr->id);
            if(!$data){
              return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
            }
            // LÃƒâ€ Ã‚Â°u lÃƒÂ¡Ã‚Â»Ã¢â‚¬Â¹ch sÃƒÂ¡Ã‚Â»Ã‚Â­
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
        return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__, 'messages.delete_fail');
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
       // Import dÃƒÂ¡Ã‚Â»Ã‚Â¯ liÃƒÂ¡Ã‚Â»Ã¢â‚¬Â¡u
       $import = new AccSuppliesGoodsTypeImport;
       Excel::import($import, $file);
       // LÃƒÂ¡Ã‚ÂºÃ‚Â¥y lÃƒÂ¡Ã‚ÂºÃ‚Â¡i dÃƒÂ¡Ã‚Â»Ã‚Â¯ liÃƒÂ¡Ã‚Â»Ã¢â‚¬Â¡u
       
       $merged = collect($rs)->push($import->getData());
       //dump($merged);
     // LÃƒâ€ Ã‚Â°u lÃƒÂ¡Ã‚Â»Ã¢â‚¬Â¹ch sÃƒÂ¡Ã‚Â»Ã‚Â­
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
    return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__, 'messages.failed_import');
   }
 }

 public function export(Request $request) {
   $type = 6;
   try{
       $arr = $request->data;
       $page = $request->page;
       //return (new HistoryActionExport($arr))->download('HistoryActionExportErmis.xlsx');
       //$myFile = Excel::download(new HistoryActionExport($arr), 'HistoryActionExportErmis.xlsx');
       $myFile = Excel::raw(new AccSuppliesGoodsTypeExport($arr,$page), \Maatwebsite\Excel\Excel::XLSX);
       $response =  array(
         'status' =>true,
         'name' => "AccSuppliesGoodsTypeExportErmis", //no extention needed
         'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($myFile) //mime type of used format
      );
      return response()->json($response);
   }catch(Exception $e){
     return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__, 'messages.failed_export');
   }
 }

}
