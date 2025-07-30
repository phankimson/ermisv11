<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankCompareCreateGeneralVoucherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    protected $default;

    public function DefaultValue($value){
        $this->default = $value;
        return $this;
    }

    public function toArray($request)
    {
        return [
            'description' => $this->transaction_description,
            'voucher_date' => $this->accounting_date,
            'accounting_date' => $this->accounting_date,
            'bank_account'=> $this->bank_account,
            'traders' => $this->corresponsive_name,
            'total_amount' => $this->debit_amount == 0 ? $this->credit_amount : $this->debit_amount,
            'total_amount_rate' => $this->debit_amount == 0 ? $this->credit_amount : $this->debit_amount,      
            'detail' => CashPaymentDetailReadResource::collection($this),  
        ];
    }
}
