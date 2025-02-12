<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CashReceiptVoucherInvoiceResource extends JsonResource
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
            'invoice' => $this->invoice,
            'date_invoice' => $this->date_invoice,
            'description'=>$this->description,  
            'total_amount'=>$this->total_amount,       
            'total_payment'=>0,    
            'status' =>  $this->status,
            'active' =>  $this->active,
        ];
    }
}
