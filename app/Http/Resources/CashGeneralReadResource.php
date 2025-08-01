<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ObjectDropDownListResource;

class CashGeneralReadResource extends JsonResource
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
            'currency' => $this->currency,
            'voucher' => $this->voucher,
            'description' => $this->description,
            'voucher_date' => $this->voucher_date,
            'accounting_date' => $this->accounting_date,
            'traders' => $this->traders,
            'subject_id' => $this->subject,
            'rate' => $this->rate,
            'object' => new ObjectDropDownListResource($this->whenLoaded('object')),
            'status' =>  $this->status,
            'detail' => CashVoucherInvoiceBindResource::collection($this->whenLoaded('vat_detail_payment')),
            'active' =>  $this->active,
        ];
    }
}
