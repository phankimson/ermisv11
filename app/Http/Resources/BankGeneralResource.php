<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankGeneralResource extends JsonResource
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
            'currency' => $this->currency,
            'voucher' => $this->voucher,
            'description' => $this->description,
            'voucher_date' => $this->voucher_date,
            'accounting_date' => $this->accounting_date,
            'traders' => $this->traders,
            'subject' => $this->subject,
            'total_amount' => $this->total_amount,
            'total_amount_rate' => $this->total_amount_rate,
            'reference' =>  $this->reference,
            'reference_by' =>  $this->reference_by,
            'status' =>  $this->status,           
            'active' =>  $this->active,
        ];
    }
}
