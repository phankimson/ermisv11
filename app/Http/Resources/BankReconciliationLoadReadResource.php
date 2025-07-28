<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankReconciliationLoadReadResource extends JsonResource
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
            'voucher_date' => $this->accounting_date,
            'description' => $this->transaction_description,
            'debit_amount' => $this->debit_amount?$this->debit_amount:0,
            'credit_amount' => $this->credit_amount?$this->credit_amount:0,
            'subject' => $this->corresponsive_name,
            'active' =>  $this->detail_id?1:0,
        ];
    }
}
