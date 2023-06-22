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
      $locale = $request->segment(1);
        return [
            'id' => $this->id,
            'subject_id' => $this->id,
            'subject_code' => $this->code,
            'subject_name' => $this->name,
            'code' => $this->code,
            'name' => $this->name,
            'address' => $this->address,
            'invoice_form' => $this->invoice_form,
            'invoice_symbol' => $this->invoice_symbol,
        ];
    }
}
