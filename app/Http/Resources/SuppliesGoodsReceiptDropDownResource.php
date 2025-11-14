<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SuppliesGoodsReceiptDropDownResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      // Make sure current locale exists.
      $locale = $request->segment(1);
      $unit_name = $locale == "vi" ? optional($this->unit)->name : optional($this->unit)->name_en;
        return (object)[
            'value' => $this->id,
            'text' => $this->code ." - ".($locale == "vi" ? $this->name :$this->name_en),
            'item_code' => $this->id,
            'unit' =>  $this->unit_id,
            'quantity' => $this->stock_check_sum_quantity,
            'unit_name' => $unit_name,
            'price' => $this->price_purchase,
            'account' => $this->stock_account,
        ];
    }
}
