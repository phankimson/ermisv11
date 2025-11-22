<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GeneralDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->inventory){
            return [
                'id' => $this->id,
                'item_code' => $this->inventory->item_code,
                'item_name' =>  $this->inventory->item_name,
                'currency' => $this->currency,
                'debit' => $this->debit()->first()->code,
                'credit' => $this->credit()->first()->code,
                'unit' => $this->inventory->unit_item->code,
                'stock' => $this->inventory->stock_issue ? optional($this->inventory->stock_issue_item)->code : optional($this->inventory->stock_receipt_item)->code,
                'price' => $this->inventory->price,
                'quantity' => $this->inventory->quantity,
                'amount' => $this->amount,
                'rate' => $this->rate,
                'amount_rate' => $this->amount_rate,
                'lot_number' => $this->lot_number,
                'expiry_date' =>  $this->expiry_date,
                'order' =>  $this->order,
                'contract' =>  $this->contract,
                'active' =>  $this->active,
            ];
        }else{
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
}
