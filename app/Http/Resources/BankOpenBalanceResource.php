<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankOpenBalanceResource extends JsonResource
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
            'balance_id' => $this->balance->count()>0 ? $this->balance->first()->id : "",
            'bank_name' => $this->bank_name,
            'bank_account' => $this->bank_account,
            'account_default' => $this->code,
            'bank' => $this->name,
            'branch' => $this->branch,
            'debit_balance' => $this->balance->count()>0? $this->balance->first()->debit_close : 0,
            'credit_balance' => $this->balance->count()>0 ? $this->balance->first()->credit_close : 0,
        ];
      
    }   

}
