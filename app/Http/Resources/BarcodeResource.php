<?php

namespace App\Http\Resources;

use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccStock;
use Illuminate\Http\Resources\Json\JsonResource;

class BarcodeResource extends JsonResource
{
      private static $data;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $locale = $request->segment(1);
        $item = collect(['value' => $this->id, 'text' =>  $this->code ." - ".($locale == "vi" ? $this->name :$this->name_en)]);
        $unit = collect(['value' => $this->unit_id, 'text' =>  $this->unit_code  ." - ".($locale == "vi" ? $this->unit : $this->unit_en)]);
        $stock = AccStock::find(self::$data['stock']);
        if(self::$data['account_default']){
            $account_default = AccAccountSystems::find(self::$data['account_default']);
        }    
        $account = AccAccountSystems::find($this->stock_account);
        if(self::$data['code_page'] === "NK" && self::$data['account_default']){           
           $account_debit =  $account ? LangDropDownResource::make($account) : LangDropDownResource::make($account_default);
           $account_credit = DefaultDropDownResource::make("");
           $price = $this->price_purchase;
        }else if(self::$data['code_page'] === "XK"  && self::$data['account_default']){          
           $account_debit = DefaultDropDownResource::make("");
           $account_credit =  $account ? LangDropDownResource::make($account) : LangDropDownResource::make($account_default);
           $price = $this->price;
        }else if(self::$data['code_page'] === "CK"  && self::$data['account_default']){          
           $account_debit = DefaultDropDownResource::make("");
           $account_credit =  $account ? LangDropDownResource::make($account) : LangDropDownResource::make($account_default);
           $price = $this->price_purchase;
        }else{    
            $account_debit = DefaultDropDownResource::make("");
            $account_credit = DefaultDropDownResource::make("");
            $price = 0;
        }
        return [
            'item_id' => $this->id,
            'item_code' => $item,
            'item_name' => $this->name,
            'code' => $this->code,
            'name' => $this->name,
            'name_en' => $this->name_en,
            'unit' => $unit ,
            'unit_name' => $unit->get('text'),
            'quantity_in_stock' => $this->quantity_in_stock,
            'price' => $price,
            'debit' =>  $account_debit ,
            'credit' =>  $account_credit,
            'stock' =>  !$stock ? DefaultDropDownResource::make("") : LangDropDownResource::make($stock),
            'subject_id' =>  $this->subject_id_credit,
            'subject_code' =>  !$this->subject_id_credit ? DefaultDropDownResource::make("") : ObjectDropDownResource::make($this->subject_credit()->first()),
            'subject_name' =>  $this->subject_name_credit,
            'case_code' =>  !$this->case_code ? DefaultDropDownResource::make("") : LangDropDownResource::make($this->case_code()->first()),
            'cost_code' =>  !$this->cost_code ? DefaultDropDownResource::make("") : LangDropDownResource::make($this->cost_code()->first()),
            'statistical_code' => !$this->statistical_code ? DefaultDropDownResource::make("") : LangDropDownResource::make($this->statistical_code()->first()),
            'work_code' =>  !$this->work_code ? DefaultDropDownResource::make("") : LangDropDownResource::make($this->work_code()->first()),
            'accounted_fast' => !$this->accounted_fast ? DefaultDropDownResource::make("") : LangDropDownResource::make($this->accounted_fast()->first()),
            'department' =>  !$this->department ? DefaultDropDownResource::make("") : LangDropDownResource::make($this->department()->first()),    
            'lot_number' => !$this->lot_number ? "" : $this->lot_number,
            'contract' => !$this->contract ? "" : $this->contract,
            'order' => !$this->order ? "" : $this->order,
        ];
    }

  //I made custom function that returns collection type
  public static function customCollection($resource, $data): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
  {
   //you can add as many params as you want.
    self::$data = $data;
    return parent::collection($resource);
  }
}
