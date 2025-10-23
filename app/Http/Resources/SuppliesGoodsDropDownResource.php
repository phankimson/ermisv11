<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SuppliesGoodsDropDownResource extends JsonResource
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
            'value' => $this['id'],
            'text' => $this['code'] ." - ".($locale == "vi" ? $this['name'] :$this['name_en']),
            'unit' => $this['unit_id'],
            'stock' => $this['stock'],
            'unit_name' => ($locale == "vi" ? $this['unit'] :$this['unit_en']),
            'quantity' => $this['quantity'],
            'account' => $this['account'],
        ];
    }
}
