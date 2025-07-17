<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ObjectDropDownListResource extends JsonResource
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
            'id' => $this->id,
            'value' => $this->id,
            'text' => $this->code.' - '. $this->name,
            'subject_id' => $this->id,
            'subject_code' => $this->code,
            'subject_name' => $this->name,
            'tax_code' => $this->tax_code,
            'code' => $this->code,
            'name' => $this->name,
            'address' => $this->address,
            'invoice_form' => $this->invoice_form,
            'invoice_symbol' => $this->invoice_symbol,
        ];
    }
}
