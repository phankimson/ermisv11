<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LangTaxDropDownListResource extends JsonResource
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
        return [
            'id' => $this->id,
            'code' => $this->vat_tax,
            'name' => $locale == "vi" ? $this->name :$this->name_en,
        ];
    }
}
