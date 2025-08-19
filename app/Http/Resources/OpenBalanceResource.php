<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OpenBalanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'balance_id' => '',
            'code' => $this->code,
            'name' => $this->name,
            'name_en' => $this->name_en,
            'parent_id' => $this->parent_id,
            'debit_amount' => 0,
            'credit_amount' => 0,
        ];
    }
}
