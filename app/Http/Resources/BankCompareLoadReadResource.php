<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankCompareLoadReadResource extends JsonResource
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
            'checkbox' => "",
            'voucher_date' => $this->accounting_date,
            'description' => $this->transaction_description,
            'debit_amount' => $this->debit_amount?$this->debit_amount:0,
            'credit_amount' => $this->credit_amount?$this->credit_amount:0,
            'subject' => $this->corresponsive_name,
            'is_checked' =>  $this->status == 2?1:0,
        ];
    }
}
