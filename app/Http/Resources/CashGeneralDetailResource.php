<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CashGeneralDetailResource extends JsonResource
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
            'description' => $this->description,
            'currency' => $this->currency,
            'debit' => $this->debit()->first()->code,
            'credit' => $this->credit()->first()->code,
            'amount' => $this->amount,
            'rate' => $this->rate,
            'amount_rate' => $this->amount_rate,
            'lot_number' => $this->lot_number,
            'expiry_date' =>  $this->expiry_date,
            'order' =>  $this->order,
            'contract' =>  $this->contract,
            'active' =>  $this->active,
        ];
    }
}
