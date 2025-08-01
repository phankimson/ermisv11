<?php

namespace App\Http\Resources;

use App\Http\Model\Casts\Decimal;
use Illuminate\Http\Resources\Json\JsonResource;

class CashVoucherInvoiceResource extends JsonResource
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
            'id' => "",
            'detail_id'=> "",
            'vat_detail_id'=> $this->id,
            'invoice' => $this->invoice,
            'date_invoice' => $this->date_invoice,
            'description'=>$this->description,  
            'total_amount'=>$this->total_amount,       
            'paid'=>(float)$this->vat_detail_payment?(float)$this->vat_detail_payment:0,    
            'remaining'=>(float)$this->total_amount-(float)$this->vat_detail_payment,  
            'payment'=>0,
            'checkbox'=>'',
            'rate'=>$this->rate,
            'payment_rate'=>0,
            'status' =>  $this->status,
            'active' =>  $this->active,
        ];
    }
}
