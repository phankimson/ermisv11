<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\AccHistoryAction;
use App\Http\Model\Menu;
use App\Http\Model\AccSuppliesGoods;
use App\Http\Model\AccSuppliesGoodsType;
use App\Http\Model\AccSuppliesGoodsDiscount;
use App\Http\Model\AccHistoryPrice;
use App\Http\Model\AccSystems;
use App\Http\Model\AccNumberCode;
use App\Http\Model\CompanySoftware;
use App\Http\Model\Company;
use Illuminate\Support\Facades\Storage;
use App\Http\Model\Imports\AccSuppliesGoodsImport;
use App\Http\Model\Exports\AccSuppliesGoodsExport;
use App\Classes\Convert;
use App\Http\Model\AccAccountSystems;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\DB;

class AccSuppliesGoodsController extends Controller
{
  protected $url;
  protected $key;
  protected $menu;
  protected $page_system;
  protected $path;
  protected $download;
  public function __construct(Request $request)
 {
     $this->url =  $request->segment(3);
     $this->key = "supplies-goods";
     $this->menu = Menu::where('code', '=', $this->key)->first();
     $this->page_system = "MAX_COUNT_CHANGE_PAGE";
     $this->path = "PATH_UPLOAD_SUPPLIES_GOODS";     
     $this->download = 'AccSuppliesGoods.xlsx';
 }

  public function show(){
    //$data = AccSuppliesGoods::with('discount')->get();
    //$unit = collect(DropDownListResource::collection(AccUnit::active()->get()));
    //$sg_type = collect(DropDownListResource::collection(AccSuppliesGoodsType::active()->get()));
    //$sg_group = collect(DropDownListResource::collection(AccSuppliesGoodsGroup::active()->get()));
    //$w_p= collect(DropDownListResource::collection(AccWarrantyPeriod::active()->get()));
    //$st= collect(DropDownListResource::collection(AccStock::active()->get()));
    //$vat_tax= collect(DropDownListResource::collection(AccVat::active()->get()));
    //$excise_tax = collect(DropDownListResource::collection(AccExcise::active()->get()));
    //$sys = AccSystems::get_systems($this->document);
    //$doc = Document::get_code($sys->value);
    //$setting_revenue = AccSettingAccountGroup::get_code($this->revenue);
    //$account_revenue = collect([]);
    //if($setting_revenue->account_group){
    //  $account_revenue = AccAccountSystems::get_code_like($doc->id,$setting_revenue->account_group);
    //}else if($setting_revenue->account_filter){
    //  $account_revenue = AccAccountSystems::get_wherein_id($doc->id,$setting_revenue->account_filter->pluck('account_systems'));
    //}
    //$setting_cost = AccSettingAccountGroup::get_code($this->cost);
    //$account_cost = collect([]);
    //if($setting_cost->account_group){
    //  $account_cost = AccAccountSystems::get_code_like($doc->id,$setting_cost->account_group);
    //}else if($setting_cost->account_filter){
    //  $account_cost = AccAccountSystems::get_wherein_id($doc->id,$setting_cost->account_filter->pluck('account_systems'));
    //}
    //$setting_stock = AccSettingAccountGroup::get_code($this->stock);
    //$account_stock = collect([]);
    //if($setting_stock->account_group){
    //  $account_stock = AccAccountSystems::get_code_like($doc->id,$setting_stock->account_group);
    //}else if($setting_stock->account_filter){
    //  $account_stock = AccAccountSystems::get_wherein_id($doc->id,$setting_stock->account_filter->pluck('account_systems'));
    //}
    $count = AccSuppliesGoods::count();
    $sys_page = AccSystems::get_systems($this->page_system);
    $paging = $count>$sys_page->value?1:0;   
    return view('acc.'.str_replace("-", "_", $this->key),['paging' => $paging, 'key' => $this->key ]);
  }

  
  public function data(Request $request){   
    $total = AccSuppliesGoods::count();
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
          $arr = AccSuppliesGoods::get_raw_skip_filter_page($skip,$perPage,$orderby,$asc,$filter_conditions);
          $total = Convert::applyFilterConditions(AccSuppliesGoods::query(), $filter_conditions)->count();
        }else{
          $arr = AccSuppliesGoods::get_raw_skip_page($skip,$perPage,$orderby,$asc); 
        }   
    $data = collect(['data' => $arr,'total' => $total]);              
    if($data){
      return response()->json($data);
    }else{
      return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
    }
  }



  public function load(Request $request){
    $type = 10;
    try{
    $req = json_decode($request->data);   
    $type = AccSuppliesGoodsType::find($req);
    if(!$type){
      return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
    }
    $data = AccNumberCode::get_code($this->key.'_'.$type->filter);
    if($data){
      return response()->json(['status'=>true,'data'=> $data]);
    }else{
      return response()->json(['status'=>false,'message'=> trans('messages.no_data_found')]);
    }
    }catch(Exception $e){
      return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__);
    }
  }

  public function load_change(Request $request){
    $type = 10;
    try{
    $req = json_decode($request->data);   
    $type = AccSuppliesGoodsType::find($req);
    if($type){
      return response()->json(['status'=>true,'stock_account'=> $type->account_default]);      
    }else{
     return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
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
      $data = AccSuppliesGoods::with('discount')->get();
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

       // KiÃƒÂ¡Ã‚ÂºÃ‚Â¿m loÃƒÂ¡Ã‚ÂºÃ‚Â¡i supplies goods Ãƒâ€žÃ¢â‚¬ËœÃƒÂ¡Ã‚Â»Ã†â€™ lÃƒâ€ Ã‚Â°u mÃƒÆ’Ã‚Â£ code
        $type_sg = AccSuppliesGoodsType::find($arr->type);
       // LÃƒâ€ Ã‚Â°u mÃƒÆ’Ã‚Â£ code tÃƒÂ¡Ã‚Â»Ã‚Â± tÃƒâ€žÃ†â€™ng
       $ir = AccNumberCode::get_code($this->key.'_'.$type_sg->filter);
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


       // LÃƒâ€ Ã‚Â°u ÃƒÂ¡Ã‚ÂºÃ‚Â£nh thÃƒÆ’Ã‚Âªm
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
         // LÃƒâ€ Ã‚Â°u lÃƒÂ¡Ã‚ÂºÃ‚Â¡i hÃƒÆ’Ã‚Â¬nh ÃƒÂ¡Ã‚ÂºÃ‚Â£nh
         $data = AccSuppliesGoods::find($arr->id);
         if($data){
         $data->image = $pathname;
         $data->save();
         }        
         //LÃƒâ€ Ã‚Â°u ÃƒÂ¡Ã‚ÂºÃ‚Â£nh lÃƒÂ¡Ã‚ÂºÃ‚Â¡i array
         $arr->image = $pathname;
       }
       //

       // LÃƒâ€ Ã‚Â°u lÃƒÂ¡Ã‚Â»Ã¢â‚¬Â¹ch sÃƒÂ¡Ã‚Â»Ã‚Â­
       $h = new AccHistoryAction();
       $h ->create([
         'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
         'user' => Auth::id(),
         'menu' => $this->menu->id,
         'url'  => $this->url,
         'dataz' => \json_encode($data)]);

       // LÃƒâ€ Ã‚Â°u lÃƒÂ¡Ã‚Â»Ã¢â‚¬Â¹ch sÃƒÂ¡Ã‚Â»Ã‚Â­ giÃƒÆ’Ã‚Â¡ mua
       $p = new AccHistoryPrice();
       $p ->create([
         'price_type' => 1, // Mua : 1 , BÃƒÆ’Ã‚Â¡n : 2
         'supplies_goods_id' => $data->id,
         'value' => $data->price_purchase]);

       // LÃƒâ€ Ã‚Â°u lÃƒÂ¡Ã‚Â»Ã¢â‚¬Â¹ch sÃƒÂ¡Ã‚Â»Ã‚Â­ giÃƒÆ’Ã‚Â¡ bÃƒÆ’Ã‚Â¡n
       $p = new AccHistoryPrice();
       $p ->create([
         'price_type' => 2, // Mua : 1 , BÃƒÆ’Ã‚Â¡n : 2
         'supplies_goods_id' => $data->id,
         'value' => $data->price]);

       // LÃƒÂ¡Ã‚ÂºÃ‚Â¥y ID vÃƒÆ’Ã‚Â  vÃƒÆ’Ã‚Â  phÃƒÆ’Ã‚Â¢n loÃƒÂ¡Ã‚ÂºÃ‚Â¡i ThÃƒÆ’Ã‚Âªm
       $arr->id = $data->id;
       $arr->t = $type;
       DB::connection(env('CONNECTION_DB_ACC'))->commit();
       broadcast(new \App\Events\DataSend($arr));
       return response()->json(['status'=>true,'message'=> trans('messages.add_success')]);
     }else if($permission['e'] == true && $arr->id){
       $type = 3;
       $data = AccSuppliesGoods::find($arr->id);
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

      //Check lÃƒÂ¡Ã‚Â»Ã¢â‚¬Â¹ch sÃƒÂ¡Ã‚Â»Ã‚Â­ giÃƒÆ’Ã‚Â¡ mua
      $check_price_purchase = AccHistoryPrice::get_product_last($data->id,1);
      if($check_price_purchase == null || $check_price_purchase->value != $arr->price_purchase){
        // LÃƒâ€ Ã‚Â°u lÃƒÂ¡Ã‚Â»Ã¢â‚¬Â¹ch sÃƒÂ¡Ã‚Â»Ã‚Â­ giÃƒÆ’Ã‚Â¡ mua
        $p = new AccHistoryPrice();
        $p ->create([
          'price_type' => 1, // Mua : 1 , BÃƒÆ’Ã‚Â¡n : 2
          'supplies_goods_id' => $data->id,
          'value' => $arr->price_purchase]);
      }


      //Check lÃƒÂ¡Ã‚Â»Ã¢â‚¬Â¹ch sÃƒÂ¡Ã‚Â»Ã‚Â­ giÃƒÆ’Ã‚Â¡ bÃƒÆ’Ã‚Â¡n
      $check_price= AccHistoryPrice::get_product_last($data->id,2);
      if($check_price == null || $check_price->value != $arr->price){
        // LÃƒâ€ Ã‚Â°u lÃƒÂ¡Ã‚Â»Ã¢â‚¬Â¹ch sÃƒÂ¡Ã‚Â»Ã‚Â­ giÃƒÆ’Ã‚Â¡ bÃƒÆ’Ã‚Â¡n
        $p = new AccHistoryPrice();
        $p ->create([
          'price_type' => 2, // Mua : 1 , BÃƒÆ’Ã‚Â¡n : 2
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
            if(!$dom){
              DB::connection(env('CONNECTION_DB_ACC'))->rollBack();
              return response()->json(['status'=>false,'message'=>trans('messages.no_data_found')]);
            }
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
      // XÃƒÆ’Ã‚Â³a cÃƒÆ’Ã‚Â¡c dÃƒÆ’Ã‚Â²ng
      if($dom_all->count()>0){
        $id_destroy = $dom_all->pluck('id');
        AccSuppliesGoodsDiscount::destroy($id_destroy);
      }
      ///////////////////

       // LÃƒÂ¡Ã‚ÂºÃ‚Â¥y lÃƒÂ¡Ã‚ÂºÃ‚Â¡i giÃƒÆ’Ã‚Â¡ trÃƒÂ¡Ã‚Â»Ã¢â‚¬Â¹ hot
       $hot_add = AccSuppliesGoodsDiscount::get_discount($data->id);
       $arr->discount = $hot_add;


      // LÃƒâ€ Ã‚Â°u ÃƒÂ¡Ã‚ÂºÃ‚Â£nh sÃƒÂ¡Ã‚Â»Ã‚Â­a
      if($request->hasFile('files')) {
        //XÃƒÆ’Ã‚Â³a ÃƒÂ¡Ã‚ÂºÃ‚Â£nh cÃƒâ€¦Ã‚Â©
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
        // LÃƒâ€ Ã‚Â°u lÃƒÂ¡Ã‚ÂºÃ‚Â¡i hÃƒÆ’Ã‚Â¬nh ÃƒÂ¡Ã‚ÂºÃ‚Â£nh
        $data = AccSuppliesGoods::find($arr->id);
        if($data){
        $data->image = $pathname;
        $data->save();
        }       
        //LÃƒâ€ Ã‚Â°u ÃƒÂ¡Ã‚ÂºÃ‚Â£nh lÃƒÂ¡Ã‚ÂºÃ‚Â¡i array
        $arr->image = $pathname;
      }
      //

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
            $data = AccSuppliesGoods::get_id_with_discount($arr->id);
            // XÃƒÆ’Ã‚Â³a lÃƒÂ¡Ã‚Â»Ã¢â‚¬Â¹ch sÃƒÂ¡Ã‚Â»Ã‚Â­ giÃƒÆ’Ã‚Â¡
            AccHistoryPrice::where('supplies_goods_id',$arr->id)->delete();
            // XÃƒÆ’Ã‚Â³a discount
            AccSuppliesGoodsDiscount::where('supplies_goods_id',$arr->id)->delete();
            // LÃƒâ€ Ã‚Â°u lÃƒÂ¡Ã‚Â»Ã¢â‚¬Â¹ch sÃƒÂ¡Ã‚Â»Ã‚Â­
            //XÃƒÆ’Ã‚Â³a ÃƒÂ¡Ã‚ÂºÃ‚Â£nh cÃƒâ€¦Ã‚Â©
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
       $import = new AccSuppliesGoodsImport;
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
       $myFile = Excel::raw(new AccSuppliesGoodsExport($arr,$page), \Maatwebsite\Excel\Excel::XLSX);
       $response =  array(
         'status' =>true,
         'name' => "AccSuppliesGoodsExportErmis", //no extention needed
         'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($myFile) //mime type of used format
      );
      return response()->json($response);
   }catch(Exception $e){
     return $this->handleControllerException($e, $type, $this->menu->id ?? 0, $this->url, __FUNCTION__, 'messages.failed_export');
   }
 }

}
