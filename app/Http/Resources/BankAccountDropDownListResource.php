<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankAccountDropDownListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return (object)[
            'id' => $this->id,
            'code' => $this->bank_account,
            'name' => $this->bank_name,
        ];
    }
}

