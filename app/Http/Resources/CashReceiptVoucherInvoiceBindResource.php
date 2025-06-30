<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CashReceiptVoucherInvoiceBindResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $vat_detail = $this->first()->vat_detail->first();
        return [
            'id' => $this->id,
            'detail_id'=> $this->detail_id,
            'vat_detail_id'=> $this->vat_detail_id,
            'invoice' => $vat_detail->invoice,
            'date_invoice' => $vat_detail->date_invoice,
            'description'=>$vat_detail->description,  
            'total_amount'=>$vat_detail->total_amount,       
            'paid'=>(float)$this->paid,    
            'remaining'=>(float)$this->remaining,  
            'payment'=>(float)$this->payment,
            'rate'=>$this->rate,
            'payment_rate'=>(float)$this->payment_rate,
            'status' =>  $vat_detail->status,
            'active' =>  $vat_detail->active,
        ];
    }
}
