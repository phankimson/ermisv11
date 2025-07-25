<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LangDropDownResource;

class BankLoadReadResource extends JsonResource
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
            'voucher' => $this->voucher,
            'voucher_date' => $this->voucher_date,
            'accounting_date' => $this->accounting_date,
            'description' => $this->description,
            'debit_amount' => $this->bank_account_debit?$this->amount:0,
            'credit_amount' => $this->bank_account_credit?$this->amount:0,
            'subject' => $this->object_name,
            'active' =>  $this->active,
        ];
    }
}
