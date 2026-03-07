<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\User;
use App\Http\Model\Menu;
use App\Http\Model\AccSystems;
use App\Http\Resources\DropDownListResource;
use App\Http\Resources\UserDropDownListResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Model\Imports\HistoryActionImport;
use App\Http\Model\Exports\AccHistoryActionExport;
use App\Classes\Convert;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\DB;

class AccHistoryActionController extends Controller
{
    protected $url;
    protected $key;
    protected $menu;
    protected $page_system;
    protected $download;
    public function __construct(Request $request)
   {
       $this->url = $request->segment(3);
       $this->key = "history-action";
       $this->menu = Menu::where('code', '=', $this->key)->first();
       $this->page_system = "MAX_COUNT_CHANGE_PAGE";
       $this->download = 'HistoryAction.xlsx';
   }

   public function show(){
      $type = 0;
      $data = AccHistoryAction::get_raw_type($type);
      $menu = collect(DropDownListResource::collection(Menu::all()));
      $user = collect(UserDropDownListResource::collection(User::all()));
      return view('global.'.str_replace("-", "_", $this->key),['data' => $data, 'user'=>$user, 'menu'=>$menu, 'key' => $this->key , 'type'=>$type]);
   }

   public function data(Request $request){   
    $ts = $request->input('ts',0);
    $total = AccHistoryAction::count();
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
          $arr = AccHistoryAction::get_raw_skip_filter_page($skip,$perPage,$orderby,$asc,$filter_conditions,$ts);
          $total = Convert::applyFilterConditions(AccHistoryAction::query(), $filter_conditions)->count();
        }else{
          $arr = AccHistoryAction::get_raw_skip_page($skip,$perPage,$orderby,$asc,$ts); 
        }     
    $data = collect(['data' => $arr,'total' => $total]);              
    if($data){
      return response()->json($data);
    }else{
      return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
    }
  }

    public function get(Request $request){
      $type = 9;
      try{
        $req = $request->data;
        $data = AccHistoryAction::get_raw_type($req);
        return response()->json(['status'=>true,'data'=> $data ]);
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
             'created_at' => 'required',
             'user' => 'required',
         ]);
      if($validator->passes()){
      if($permission['a'] == true && !$arr->id ){
        $type = 2;
        $data = new AccHistoryAction();
        $data->type = $arr->type;
        $data->url = $arr->url;
        $data->user = $arr->user;
        $data->menu = $arr->menu;
        $data->dataz = $arr->dataz;
        $data->created_at = $arr->created_at;
        $data->save();

        // Lay lich su
        //$this->create_history($type,Auth::id(),$this->menu->id,$this->url,$data);
        //
        // Lay id de truyen len client
        $arr->id = $data->id;
        $arr->t = $type;
        DB::connection(env('CONNECTION_DB_ACC'))->commit();
        broadcast(new \App\Events\DataSend($arr));
        return response()->json(['status'=>true,'message'=> trans('messages.add_success')]);
      }else if($permission['e'] == true && $arr->id){
        $type = 3;
        $data = AccHistoryAction::find($arr->id);
        if(!$data){
          return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
        }
        // Lay lich su
       //$this->create_history($type,Auth::id(),$this->menu->id,$this->url,$data);
        //
        $data->url = $arr->url;
        $data->type = $arr->type;
        $data->user = $arr->user;
        $data->menu = $arr->menu;
        $data->dataz = $arr->dataz;
        $data->created_at = $arr->created_at;
        $data->save();
        // Phan loai sua doi de truyen len client
        $arr->t = $type;
        DB::connection(env('CONNECTION_DB_ACC'))->commit();
        broadcast(new \App\Events\DataSend($arr));
        return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
        }else{
         return response()->json(['status'=>false,'message'=> trans('messages.you_are_not_permission')]);
        }
      }else{
        DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
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
             $data = AccHistoryAction::find($arr->id);
             if(!$data){
                return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
              }
             // Luu lich su
             //$this->create_history($type,Auth::id(),$this->menu->id,$this->url,$data);
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
        // Import du lieu
        Excel::import(new HistoryActionImport, $file);
        // Lay lai du lieu
        $array = AccHistoryAction::get_raw_type($rs->ts);

        $merged = collect($rs)->push($array);
        //dump($merged);
      // Luu lich su
      //  $type = 5;
       //$this->create_history($type,Auth::id(),$this->menu->id,$this->url,$merged);
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
        //return (new HistoryActionExport($arr))->download('HistoryActionExportErmis.xlsx');
        //$myFile = Excel::download(new HistoryActionExport($arr), 'HistoryActionExportErmis.xlsx');
        $myFile = Excel::raw(new AccHistoryActionExport($arr), \Maatwebsite\Excel\Excel::XLSX);
        $response =  array(
          'status' =>true,
          'name' => "HistoryActionExportErmis", //no extention needed
          'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($myFile) //mime type of used format
       );
       return response()->json($response);
    }catch(Exception $e){
      return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__, 'messages.failed_export');
    }
  }
}
