<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CashReceiptDetailReadResource;
use App\Http\Resources\ObjectDropDownListResource;
use App\Http\Resources\TaxReadResource;

class CashReceiptGeneralReadResource extends JsonResource
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
            'total_amount' => $this->total_amount,
            'total_amount_rate' => $this->total_amount_rate,
            'reference' =>  $this->reference,
            'reference_by' =>  $this->reference_by,
            'status' =>  $this->status,
            'detail' => CashReceiptDetailReadResource::collection($this->whenLoaded('detail')),
            'tax' => TaxReadResource::collection($this->whenLoaded('tax')),
            'active' =>  $this->active,
        ];
    }
}