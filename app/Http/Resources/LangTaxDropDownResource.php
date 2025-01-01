<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LangTaxDropDownResource extends JsonResource
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
            'value' => $this->id,
            'text' => $this->code ." - ".($locale == "vi" ? $this->name :$this->name_en),
            'vat_tax' => $this->vat_tax,
        ];
    }
}
