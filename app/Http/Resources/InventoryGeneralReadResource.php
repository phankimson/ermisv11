<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\InventoryDetailReadResource;
use App\Http\Resources\ObjectDropDownListResource;

class InventoryGeneralReadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $stock = $this->whenLoaded('detail')->first()->inventory->stock_receipt ? $this->whenLoaded('detail')->first()->inventory->stock_receipt : $this->whenLoaded('detail')->first()->inventory->stock_issue;
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
            'stock'=> $stock,
            'object' => $this->whenLoaded('object')?new ObjectDropDownListResource($this->whenLoaded('object')):new ObjectDropDownDefaultListResource(""),
            'total_quantity' => $this->total_quantity,
            'total_amount' => $this->total_amount,
            'total_amount_rate' =>  $this->total_amount,
            'reference' =>  $this->reference,
            'reference_by' =>  $this->reference_by,
            'attach' =>  $this->whenLoaded('attach'),
            'status' =>  $this->status,
            'detail' => InventoryDetailReadResource::collection($this->whenLoaded('detail')),
            'active' =>  $this->active,
        ];
    }
}
