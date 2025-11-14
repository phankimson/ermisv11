<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SuppliesGoodsIssueDropDownResource extends JsonResource
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
        return (object)[
            'value' => $this['id'], // Lấy id của bảng stock_check
            'text' => $this['code'] ." - ".($locale == "vi" ? $this['name'] :$this['name_en']),
            'item' => $this['item_id'],
            'item_code' => $this['id'],
            'unit' => $this['unit_id'],
            'stock' => $this['stock'],
            'unit_name' => ($locale == "vi" ? $this['unit'] :$this['unit_en']),
            'quantity' => $this['quantity'],
            'price' => $this['price'],
            'account' => $this['account'],
        ];
    }
}
