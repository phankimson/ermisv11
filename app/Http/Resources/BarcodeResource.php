<?php

namespace App\Http\Resources;

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
        $stock = AccStock::find(self::$data);
        return [
            'id' => $this->id,
            'item_code' => $item,
            'item_name' => $this->name,
            'code' => $this->code,
            'name' => $this->name,
            'name_en' => $this->name_en,
            'unit' => $unit ,
            'unit_name' => $this->name,
            'quantity_in_stock' => $this->quantity_in_stock,
            'price' => $this->price,
            'price_purchase' => $this->price_purchase,
            'debit' =>  !$this->debit ? DefaultDropDownResource::make("") : LangDropDownResource::make($this->debit()->first()),
            'credit' =>  !$this->credit ? DefaultDropDownResource::make("") : LangDropDownResource::make($this->credit()->first()),
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
