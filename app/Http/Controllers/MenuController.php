<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\HistoryAction;
use App\Http\Model\User;
use App\Http\Model\Menu;
use App\Http\Model\Error;
use App\Http\Model\Software;
use App\Http\Resources\DropDownListResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Model\Imports\MenuImport;
use App\Http\Model\Exports\MenuExport;
use App\Classes\Convert;
use Excel;

class MenuController extends Controller
{
    public function __construct(Request $request)
   {
       $this->url = $request->segment(3);
       $this->key = "menu";
       $this->menu = Menu::where('code', '=', $this->key)->first();
   }

   public function show(){
      $type = Software::first();
      $data = Menu::get_raw_type($type->id);
      $software = collect(DropDownListResource::collection(SoftWare::all()));
      return view('manage.menu',['data' => $data, 'key' => $this->key , 'type' => $type->id ,'software' => $software]);
   }
    public function get(Application $app,Request $request){
      $type = 9;
      try{
        $req = $request->data;
        $locale = $app->getLocale();
        $name = '';
        if($locale == 'vi'){
          $name = 'name';
        }else{
          $name = 'name_'.$locale;
        };
        $data = Menu::get_raw_type($req);
        $datatb = Menu::get_menu_droplist($req,$name);
        return response()->json(['status'=>true,'data'=> $data ,'datatb' => $datatb ]);
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
             'code' => ['required','max:100'],
             'name' => 'required',
             'name_en' => 'required',
         ]);
      if($validator->passes()){
      $code_check = Menu::WhereCheck1('code',$arr->code,'link',$arr->link,'id',$arr->id)->first();
      if($code_check == null){
      if($permission['a'] == true && !$arr->id ){
        $type = 2;
        $data = new Menu();
        $data->type = $arr->type;
        $data->parent_id = $arr->parent_id;
        $data->code = $arr->code;
        $data->name = $arr->name;
        $data->name_en = $arr->name_en;
        $data->icon = $arr->icon;
        $data->link = $arr->link;
        $data->position = Convert::intDefaultformat($arr->position);
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
        broadcast(new \App\Events\DataSend($arr));
        return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
      }else if($permission['e'] == true && $arr->id){
        $type = 3;
        $data = Menu::find($arr->id);
        // Lưu lịch sử ---- NOT EDIT
        $h = new HistoryAction();
        $h ->create([
          'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
          'user' => Auth::id(),
          'menu' => $this->menu->id,
          'url' => $this->url,
          'dataz' => \json_encode($data)]);
        /////////////////////////////
        $data->type = $arr->type;
        $data->parent_id = $arr->parent_id;
        $data->code = $arr->code;
        $data->name = $arr->name;
        $data->name_en = $arr->name_en;
        $data->icon = $arr->icon;
        $data->link = $arr->link;
        $data->position = Convert::intDefaultformat($arr->position);
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
           return response()->json(['status'=>false,'message'=> trans('messages.code_url_is_already')]);
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
             $data = Menu::find($arr->id);
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
    return Storage::download('public/downloadFile/Menu.xlsx');
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
        Excel::import(new MenuImport, $file);
        // Lấy lại dữ liệu
        $array = Menu::get_raw_type($rs->ts);

        // Import dữ liệu bằng collection
        $results = Excel::toCollection(new MenuImport, $file);
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
        $myFile = Excel::raw(new MenuExport($arr), \Maatwebsite\Excel\Excel::XLSX);
        $response =  array(
          'status' =>true,
          'name' => "MenuExportErmis", //no extention needed
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
