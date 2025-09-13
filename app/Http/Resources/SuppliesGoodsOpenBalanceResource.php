<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SuppliesGoodsOpenBalanceResource extends JsonResource
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
            'code' => $this->code,
            'name' => $this->name,
            'unit' => $this->unit_name,
            'account_default' => $this->account_default,
            'quantity' => $this->balance->count()>0? $this->balance->first()->quantity_close : 0,
            'amount' => $this->balance->count()>0? $this->balance->first()->amount_close : 0,            
        ];
      
    } 

}
