<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankDropDownResource extends JsonResource
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
        return (object)[
            'value' => $this->id,
            'text' => $this->bank_account ." - ".$this->bank_name,
        ];
    }
}
