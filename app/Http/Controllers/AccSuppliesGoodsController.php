<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\User;
use App\Http\Model\Menu;
use App\Http\Model\AccSuppliesGoods;
use App\Http\Model\AccSuppliesGoodsType;
use App\Http\Model\AccSuppliesGoodsGroup;
use App\Http\Model\AccSuppliesGoodsDiscount;
use App\Http\Model\AccHistoryPrice;
use App\Http\Model\AccUnit;
use App\Http\Model\AccStock;
use App\Http\Model\AccVat;
use App\Http\Model\AccExcise;
use App\Http\Model\AccSystems;
use App\Http\Model\AccNumberCode;
use App\Http\Model\CompanySoftware;
use App\Http\Model\Company;
use App\Http\Model\Document;
use App\Http\Model\AccWarrantyPeriod;
use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccSettingAccountGroup;
use App\Http\Model\Error;
use App\Http\Resources\DropDownListResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Model\Imports\AccSuppliesGoodsImport;
use App\Http\Model\Exports\AccSuppliesGoodsExport;
use App\Classes\Convert;
use Illuminate\Support\Str;
use Excel;
use File;

class AccSuppliesGoodsController extends Controller
{
  public function __construct(Request $request)
 {
     $this->url =  $request->segment(3);
     $this->key = "supplies-goods";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->path = "PATH_UPLOAD_SUPPLIES_GOODS";
     $this->document = "DOCUMENT_TAX";
     $this->revenue = "DT";
     $this->cost = "CP";
     $this->stock = "KH";
 }

  public function show(Request $request){
    $type = 2;
    $mysql2 = $request->session()->get('mysql2');
    config(['database.connections.mysql2' => $mysql2]);
    $data = AccSuppliesGoods::with('discount')->get();
    $unit = collect(DropDownListResource::collection(AccUnit::active()->get()));
    $sg_type = collect(DropDownListResource::collection(AccSuppliesGoodsType::active()->get()));
    $sg_group = collect(DropDownListResource::collection(AccSuppliesGoodsGroup::active()->get()));
    $w_p= collect(DropDownListResource::collection(AccWarrantyPeriod::active()->get()));
    $st= collect(DropDownListResource::collection(AccStock::active()->get()));
    $vat_tax= collect(DropDownListResource::collection(AccVat::active()->get()));
    $excise_tax = collect(DropDownListResource::collection(AccExcise::active()->get()));
    $sys = AccSystems::get_systems($this->document);
    $doc = Document::get_code($sys->value);
    $setting_revenue = AccSettingAccountGroup::get_code($this->revenue);
    $account_revenue = collect([]);
    if($setting_revenue->account_group){
      $account_revenue = AccAccountSystems::get_code_like($doc->id,$setting_revenue->account_group);
    }else if($setting_revenue->account_filter){
      $account_revenue = AccAccountSystems::get_wherein_id($doc->id,$setting_revenue->account_filter->pluck('account_systems'));
    }
    $setting_cost = AccSettingAccountGroup::get_code($this->cost);
    $account_cost = collect([]);
    if($setting_cost->account_group){
      $account_cost = AccAccountSystems::get_code_like($doc->id,$setting_cost->account_group);
    }else if($setting_cost->account_filter){
      $account_cost = AccAccountSystems::get_wherein_id($doc->id,$setting_cost->account_filter->pluck('account_systems'));
    }
    $setting_stock = AccSettingAccountGroup::get_code($this->stock);
    $account_stock = collect([]);
    if($setting_stock->account_group){
      $account_stock = AccAccountSystems::get_code_like($doc->id,$setting_stock->account_group);
    }else if($setting_stock->account_filter){
      $account_stock = AccAccountSystems::get_wherein_id($doc->id,$setting_stock->account_filter->pluck('account_systems'));
    }
    return view('acc.supplies_goods',['data' => $data, 'key' => $this->key ,'sg_type' =>$sg_type,'sg_group' =>$sg_group,"w_p"=>$w_p,'unit'=>$unit,'st'=>$st,'vat_tax'=>$vat_tax,'excise_tax'=>$excise_tax,'s_a'=>$account_stock,'r_a'=>$account_revenue,'c_a'=>$account_cost ]);
  }


  public function load(Request $request){
    $type = 10;
    try{
    $req = json_decode($request->data);
    $mysql2 = $request->session()->get('mysql2');
    config(['database.connections.mysql2' => $mysql2]);
    $type = AccSuppliesGoodsType::find($req);
    $data = AccNumberCode::get_code($this->key.'_'.$type->filter);
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
      $data = AccSuppliesGoods::with('discount')->get();
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
  $hot = $arr->hot;
  $validator = Validator::make(collect($arr)->toArray(),[
            'code' => ['required','max:50'],
            'name' => 'required',
        ]);
     if($validator->passes()){
      $code_check = AccSuppliesGoods::WhereCheck('code',$arr->code,'id',$arr->id)->first();
      if($code_check == null){
     if($permission['a'] == true && !$arr->id ){
       $type = 2;
       $data = new AccSuppliesGoods();
       $data->code = $arr->code;
       $data->name = $arr->name;
       $data->name_en = $arr->name_en;
       $data->description = $arr->description;
       $data->unit_id = $arr->unit_id;
       $data->type = $arr->type;
       $data->group = $arr->group;
       $data->interpretations_buy = $arr->interpretations_buy;
       $data->interpretations_sell = $arr->interpretations_sell;
       $data->warranty_period = $arr->warranty_period;
       $data->minimum_stock_quantity = $arr->minimum_stock_quantity;
       $data->maximum_stock_quantity = $arr->maximum_stock_quantity;
       $data->origin = $arr->origin;
       $data->stock_default = $arr->stock_default;
       $data->stock_account = $arr->stock_account;
       $data->revenue_account = $arr->revenue_account;
       $data->cost_account = $arr->cost_account;
       $data->percent_purchase_discount = $arr->percent_purchase_discount;
       $data->purchase_discount = $arr->purchase_discount;
       $data->price_purchase = $arr->price_purchase;
       $data->price = $arr->price;
       $data->vat_tax = $arr->vat_tax;
       $data->import_tax = $arr->import_tax;
       $data->export_tax = $arr->export_tax;
       $data->excise_tax = $arr->excise_tax;
       $data->identity = $arr->identity;
       $data->active = $arr->active;
       $data->save();

       // Lưu mã code tự tăng
       $ir = AccNumberCode::get_code($this->key.'_'.$arr->type);
       $ir->number = $ir->number + 1;
       $ir->save();


       // Save Handsontable
       foreach($hot as $l){
         if($l['1']!=''&&isset($l['2'])!=''){
            $dom = new AccSuppliesGoodsDiscount();
            $dom->supplies_goods_id = $data->id;
            $dom->quantity_start = $l['1'];
            $dom->quantity_end = $l['2'];
            $dom->amount_discount = $l['3'];
            $dom->percent_discount = $l['4'];
            $dom->active = 1;
            $dom->save();
         }
       }
       ///////////////////


       // Lưu ảnh thêm
       if($request->hasFile('files')) {
         $files = $request->file('files');
         $filename = $files->getClientOriginalName();
         $sys = AccSystems::get_systems($this->path);
         $path = public_path().'/'.$sys->value.'/'.$arr->com.'/'. $arr->id;
         $pathname = $sys->value . $arr->com.'/'. $arr->id.'/'.$filename;
         if(!File::isDirectory($path)){
         File::makeDirectory($path, 0777, true, true);
         }
         $upload_success = $files->move($path, $filename);
         // Lưu lại hình ảnh
         $data = AccSuppliesGoods::find($arr->id);
         $data->image = $pathname;
         $data->save();
         //Lưu ảnh lại array
         $arr->image = $pathname;
       }
       //

       // Lưu lịch sử
       $h = new AccHistoryAction();
       $h ->create([
         'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
         'user' => Auth::id(),
         'menu' => $this->menu->id,
         'url'  => $this->url,
         'dataz' => \json_encode($data)]);

       // Lưu lịch sử giá mua
       $p = new AccHistoryPrice();
       $p ->create([
         'price_type' => 1, // Mua : 1 , Bán : 2
         'supplies_goods_id' => $data->id,
         'value' => $data->price_purchase]);

       // Lưu lịch sử giá bán
       $p = new AccHistoryPrice();
       $p ->create([
         'price_type' => 2, // Mua : 1 , Bán : 2
         'supplies_goods_id' => $data->id,
         'value' => $data->price]);

       // Lấy ID và và phân loại Thêm
       $arr->id = $data->id;
       $arr->t = $type;
       broadcast(new \App\Events\DataSend($arr));
       return response()->json(['status'=>true,'message'=> trans('messages.update_success')]);
     }else if($permission['e'] == true && $arr->id){
       $type = 3;
       $data = AccSuppliesGoods::find($arr->id);
       // Lưu lịch sử
       $h = new AccHistoryAction();
       $h ->create([
         'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
         'user' => Auth::id(),
         'menu' => $this->menu->id,
         'url'  => $this->url,
         'dataz' => \json_encode($data)]);
      //

      //Check lịch sử giá mua
      $check_price_purchase = AccHistoryPrice::get_product_last($data->id,1);
      if($check_price_purchase == null || $check_price_purchase->value != $arr->price_purchase){
        // Lưu lịch sử giá mua
        $p = new AccHistoryPrice();
        $p ->create([
          'price_type' => 1, // Mua : 1 , Bán : 2
          'supplies_goods_id' => $data->id,
          'value' => $arr->price_purchase]);
      }


      //Check lịch sử giá bán
      $check_price= AccHistoryPrice::get_product_last($data->id,2);
      if($check_price == null || $check_price->value != $arr->price){
        // Lưu lịch sử giá bán
        $p = new AccHistoryPrice();
        $p ->create([
          'price_type' => 2, // Mua : 1 , Bán : 2
          'supplies_goods_id' => $data->id,
          'value' => $arr->price]);
      }


      $data->code = $arr->code;
      $data->name = $arr->name;
      $data->name_en = $arr->name_en;
      $data->description = $arr->description;
      $data->unit_id = $arr->unit_id;
      $data->type = $arr->type;
      $data->group = $arr->group;
      $data->interpretations_buy = $arr->interpretations_buy;
      $data->interpretations_sell = $arr->interpretations_sell;
      $data->warranty_period = $arr->warranty_period;
      $data->minimum_stock_quantity = $arr->minimum_stock_quantity;
      $data->maximum_stock_quantity = $arr->maximum_stock_quantity;
      $data->origin = $arr->origin;
      $data->stock_default = $arr->stock_default;
      $data->stock_account = $arr->stock_account;
      $data->revenue_account = $arr->revenue_account;
      $data->cost_account = $arr->cost_account;
      $data->percent_purchase_discount = $arr->percent_purchase_discount;
      $data->purchase_discount = $arr->purchase_discount;
      $data->price_purchase = $arr->price_purchase;
      $data->price = $arr->price;
      $data->vat_tax = $arr->vat_tax;
      $data->import_tax = $arr->import_tax;
      $data->export_tax = $arr->export_tax;
      $data->excise_tax = $arr->excise_tax;
      $data->identity = $arr->identity;
      $data->active = $arr->active;
      $data->save();


      $dom_all = AccSuppliesGoodsDiscount::get_discount($data->id);
      //return dd($hot);
      // Save Handsontable
      foreach($hot as $l){
        if($l['1']!=null&&isset($l['2'])!=null){
          if($l['0'] == null){
            $dom = new AccSuppliesGoodsDiscount();
            $dom->active = 1;
          }else{
            $dom = AccSuppliesGoodsDiscount::find($l['0']);
            $dom_all = $dom_all->filter(function ($item) use ($l) {
               return $item->id != $l['0'];
           });
          }
           $dom->supplies_goods_id = $data->id;
           $dom->quantity_start = $l['1'];
           $dom->quantity_end = $l['2'];
           $dom->amount_discount = $l['3'];
           $dom->percent_discount = $l['4'];
           $dom->save();
        }
      }
      // Xóa các dòng
      if($dom_all->count()>0){
        $id_destroy = $dom_all->pluck('id');
        AccSuppliesGoodsDiscount::destroy($id_destroy);
      }
      ///////////////////

       // Lấy lại giá trị hot
       $hot_add = AccSuppliesGoodsDiscount::get_discount($data->id);
       $arr->discount = $hot_add;


      // Lưu ảnh sửa
      if($request->hasFile('files')) {
        //Xóa ảnh cũ
        if(File::exists(public_path($data->image)) && $data->image != 'addon/img/placehold/100.png'){
           File::delete(public_path($data->image));
        };

        $files = $request->file('files');
        $filename = $files->getClientOriginalName();
        $sys = AccSystems::get_systems($this->path);
        $path = public_path().'/'.$sys->value.'/'.$arr->com.'/'. $arr->id;
        $pathname = $sys->value .$arr->com.'/'. $arr->id.'/'.$filename;
        if(!File::isDirectory($path)){
        File::makeDirectory($path, 0777, true, true);
        }
        $upload_success = $files->move($path, $filename);
        // Lưu lại hình ảnh
        $data = AccSuppliesGoods::find($arr->id);
        $data->image = $pathname;
        $data->save();
        //Lưu ảnh lại array
        $arr->image = $pathname;
      }
      //

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
            $data = AccSuppliesGoods::get_id_with_discount($arr->id);
            // Xóa lịch sử giá
            AccHistoryPrice::where('supplies_goods_id',$arr->id)->delete();
            // Xóa discount
            AccSuppliesGoodsDiscount::where('supplies_goods_id',$arr->id)->delete();
            // Lưu lịch sử
            //Xóa ảnh cũ
            if(File::exists(public_path($data->image)) && $data->image != 'addon/img/placehold/100.png'){
               File::delete(public_path($data->image));
            };
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
   return Storage::download('public/downloadFile/AccSuppliesGoods.xlsx');
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
       Excel::import(new AccSuppliesGoodsImport, $file);
       // Lấy lại dữ liệu
       $array = AccSuppliesGoods::with('discount')->get();

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
       $myFile = Excel::raw(new AccSuppliesGoodsExport($arr,$mysql2['database']), \Maatwebsite\Excel\Excel::XLSX);
       $response =  array(
         'status' =>true,
         'name' => "AccSuppliesGoodsExportErmis", //no extention needed
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
