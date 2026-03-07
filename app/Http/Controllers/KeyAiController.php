<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\Systems;
use App\Http\Model\Menu;
use App\Http\Model\KeyAi;
use Illuminate\Support\Facades\Storage;
use App\Http\Model\Imports\KeyAiImport;
use App\Http\Model\Exports\KeyAiExport;
use App\Classes\Convert;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\HistoryTraits;

class KeyAiController extends Controller
{
  use HistoryTraits;
  protected $url;
  protected $key;
  protected $menu;
  protected $page_system;
  protected $download;
  public function __construct(Request $request)
 {
     $this->url = $request->segment(3);
     $this->key = "key-ai";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->page_system = "MAX_COUNT_CHANGE_PAGE";
     $this->download = "KeyAi.xlsx"; // File download name
 }

  public function show(){
    //$data = KeyAi::get_raw();
    $count = KeyAi::count();
    $sys_page = Systems::get_systems($this->page_system);
    $paging = $count>$sys_page->value?1:0; 
    return view('manage.'.str_replace("-", "_", $this->key),['paging' => $paging, 'key' => $this->key  ]);
  }

  
  public function data(Request $request){    
    $total = KeyAi::count();
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
          $filter_conditions = Convert::parseFilterConditions($filter);
          if($filter_conditions === null){
            return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
          }
          $arr = KeyAi::get_raw_skip_filter_page($skip,$perPage,$orderby,$asc,$filter_conditions);
          $total = Convert::applyFilterConditions(KeyAi::query(), $filter_conditions)->count();
        }else{
          $arr = KeyAi::get_raw_skip_page($skip,$perPage,$orderby,$asc);    
        } 
    $data = collect(['data' => $arr,'total' => $total]);            
    if($data){
      return response()->json($data);
    }else{
      return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
    }
  }


  public function save(Request $request){
    $type = 0;
    try{
    DB::beginTransaction();
    $permission = $request->session()->get('per');
    $arr = json_decode($request->data);
    $validator = Validator::make(collect($arr)->toArray(),[
            'code' => ['required','max:50'],
            'name' => 'required',
            'name_en' => 'required',
        ]);
     if($validator->passes()){
       $code_check = KeyAi::WhereCheck('code',$arr->code,'id',$arr->id)->first();
       if($code_check == null){
     if($permission['a'] == true && !$arr->id ){
       $type = 2;
       $data = new KeyAi();
       $data->code = $arr->code;
       $data->name = $arr->name;
       $data->name_en = $arr->name_en;
       $data->content = $arr->content;
       $data->count = $arr->count;
       $data->field = $arr->field;
       $data->crit = $arr->crit;
       $data->crit_en = $arr->crit_en;
       $data->active = $arr->active;
       $data->save();

       // Luu lich su them moi
       $this->create_history($type,Auth::id(),$this->menu->id,$this->url,$data);

       // Lay ID vừa luu de truyền lên socket
       $arr->id = $data->id;
       $arr->t = $type;
       DB::commit();  
       broadcast(new \App\Events\DataSend($arr));
       return response()->json(['status'=>true,'message'=> trans('messages.add_success')]);
     }else if($permission['e'] == true && $arr->id){
       $type = 3;
       $data = KeyAi::find($arr->id);
       if(!$data){
          return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
        }
       // Luu lich su sua doi
       $ $this->create_history($type,Auth::id(),$this->menu->id,$this->url,$data);
      //
      $data->code = $arr->code;
      $data->name = $arr->name;
      $data->name_en = $arr->name_en;
      $data->content = $arr->content;
      $data->count = $arr->count;
      $data->field = $arr->field;
      $data->crit = $arr->crit;
      $data->crit_en = $arr->crit_en;
      $data->active = $arr->active;
      $data->save();
       // Phan loai sua doi de truyen len socket
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
       return response()->json(['status'=>false,'error'=>$validator->getMessageBag()->toArray() ,'message'=>trans('messages.error')]);
     }
    }catch(Exception $e){
      DB::rollBack();
      return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
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
            $data = KeyAi::find($arr->id);
            if(!$data){
              return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
            }
            // Luu lich su xoa
            $this->create_history($type,Auth::id(),$this->menu->id,$this->url,$data);
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
        return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__, 'messages.delete_fail');
      }
 }

 public function DownloadExcel(){
   return Storage::download('public/downloadFile/'.$this->download);
 }

 public function import(Request $request) {
   $type = 5;
   try{
    DB::beginTransaction();
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
       // Import du lieu
       $import = new KeyAiImport;
       Excel::import($import, $file);
       // Lay lai du lieu
      
       $merged = collect($rs)->push($import->getData());
       //dump($merged);
     // Luu lich su
      $this->create_history($type,Auth::id(),$this->menu->id,$this->url,$merged);
     //
     //Storage::delete($savePath.$filename);
     DB::commit();
     broadcast(new \App\Events\DataSendCollection($merged));
     return response()->json(['status'=>true,'message'=> trans('messages.success_import')]);
    }else{
    return response()->json(['status'=>false,'message'=> trans('messages.incorrect_file')]);
    } 
     }else{
       return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
     }
   }catch(Exception $e){
    DB::rollBack();
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
       $myFile = Excel::raw(new KeyAiExport($arr,$page), \Maatwebsite\Excel\Excel::XLSX);
       $response =  array(
         'status' =>true,
         'name' => "KeyAiExportErmis", //no extention needed
         'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($myFile) //mime type of used format
      );
      return response()->json($response);
   }catch(Exception $e){
     return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__, 'messages.failed_export');
   }
 }

}
