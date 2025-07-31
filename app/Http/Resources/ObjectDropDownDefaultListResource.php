<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ObjectDropDownDefaultListResource extends JsonResource
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
        return [
            'id' => 0,
            'value' => 0,
            'text' => "",
            'subject_id' => 0,
            'subject_code' => "",
            'subject_name' => "",
            'tax_code' => "",
            'code' => "",
            'name' => "",
            'address' => "",
            'invoice_form' => "",
            'invoice_symbol' => "",
        ];
    }
}
