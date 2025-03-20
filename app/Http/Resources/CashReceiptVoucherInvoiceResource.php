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
            'paid'=>$this->vat_detail_payment_sum_payment?$this->vat_detail_payment_sum_payment:0,    
            'remaining'=>$this->total_amount-$this->vat_detail_payment_sum_payment,  
            'payment'=>0,
            'rate'=>$this->rate,
            'status' =>  $this->status,
            'active' =>  $this->active,
        ];
    }
}
