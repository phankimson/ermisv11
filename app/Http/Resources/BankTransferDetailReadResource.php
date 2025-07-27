<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LangDropDownResource;
use App\Http\Resources\BankDropDownResource;
use App\Http\Resources\DefaultDropDownResource;

class BankTransferDetailReadResource extends JsonResource
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
            'debit' => LangDropDownResource::make($this->debit()->first()),
            'credit' => LangDropDownResource::make($this->credit()->first()),
            'amount' => $this->amount,
            'rate' => $this->rate,
            'amount_rate' => $this->amount_rate,              
            'status' =>  $this->status,
            'active' =>  $this->active,
        ];
    }
}
