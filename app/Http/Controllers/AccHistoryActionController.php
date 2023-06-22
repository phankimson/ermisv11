<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\User;
use App\Http\Model\Menu;
use App\Http\Model\Error;
use App\Http\Resources\DropDownListResource;
use App\Http\Resources\UserDropDownListResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Model\Imports\HistoryActionImport;
use App\Http\Model\Exports\AccHistoryActionExport;
use App\Classes\Convert;
use Excel;

class AccHistoryActionController extends Controller
{
    public function __construct(Request $request)
   {
       $this->url = $request->segment(3);
       $this->key = "history-action";
       $this->menu = Menu::where('code', '=', $this->key)->first();
   }

   public function show(){
      $type = 0;
      $data = AccHistoryAction::get_raw_type($type);
      $menu = collect(DropDownListResource::collection(Menu::all()));
      $user = collect(UserDropDownListResource::collection(User::all()));
      return view('global.history_action',['data' => $data, 'user'=>$user, 'menu'=>$menu, 'key' => $this->key , 'type'=>$type]);
   }
    public function get(Request $request){
      $type = 9;
      try{
        $req = $request->data;
        $data = AccHistoryAction::get_raw_type($req);
        return response()->json(['status'=>true,'data'=> $data ]);
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

   public function save(Request $request){
     $type = 0;
     try{
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

        // Lưu lịch sử
        //$h = new HistoryAction();
        //$h ->create([
        //  'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
        //  'user' => Auth::id(),
        //  'menu' => $this->menu->id,
        //  'dataz' => \json_encode($data)]);
        //
        // Lấy ID và và phân loại Thêm
        $arr->id = $data->id;
        $arr->t = $type;
        broadcast(new \App\Events\DataSend($arr));
        return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
      }else if($permission['e'] == true && $arr->id){
        $type = 3;
        $data = AccHistoryAction::find($arr->id);
        // Lưu lịch sử
        //$h = new HistoryAction();
        //$h ->create([
        //  'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
        //  'user' => Auth::id(),
        //  'menu' => $this->menu->id,
        //  'dataz' => \json_encode($data)]);
        //
        $data->url = $arr->url;
        $data->type = $arr->type;
        $data->user = $arr->user;
        $data->menu = $arr->menu;
        $data->dataz = $arr->dataz;
        $data->created_at = $arr->created_at;
        $data->save();
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
             $data = AccHistoryAction::find($arr->id);
             // Lưu lịch sử
             //$h = new HistoryAction();
             //$h ->create([
               //'type' => 4, // Add : 2 , Edit : 3 , Delete : 4
               //'user' => Auth::id(),
               //'menu' => $this->menu->id,
               //'dataz' => \json_encode($data)]);
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
           'url' => $this->url,
           'check' => 0 ]);
         return response()->json(['status'=>false,'message'=> trans('messages.delete_fail').' '.$e->getMessage()]);
       }
  }
  public function DownloadExcel(Request $request){
    return Storage::download('public/downloadFile/HistoryAction.xlsx');
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
        Excel::import(new HistoryActionImport, $file);
        // Lấy lại dữ liệu
        $array = AccHistoryAction::get_raw_type($rs->ts);

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
      //  $type = 5;
      //$h = new HistoryAction();
      //$h ->create([
      //  'type' => $type, // Add : 2 , Edit : 3 , Delete : 4, Import : 5
      //  'user' => Auth::id(),
      //  'menu' => $this->menu->id,
      //  'dataz' => \json_encode($merged)]);
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
        $myFile = Excel::raw(new AccHistoryActionExport($arr), \Maatwebsite\Excel\Excel::XLSX);
        $response =  array(
          'status' =>true,
          'name' => "HistoryActionExportErmis", //no extention needed
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
