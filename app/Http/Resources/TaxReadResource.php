<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LangTaxDropDownListResource;
use App\Http\Resources\DefaultCodeDropDownListResource;

class TaxReadResource extends JsonResource
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
            'description' => $this->description,
            'date_invoice' => $this->date_invoice,
            'invoice_form' => $this->invoice_form,
            'invoice_symbol' => $this->invoice_symbol,
            'invoice' => $this->invoice,
            'subject_code' => $this->subject_code,
            'subject_name' => $this->subject_name,
            'address' =>  $this->address,
            'tax_code' =>  $this->tax_code,
            'vat_type' =>  !$this->vat_type ? DefaultDropDownResource::make($this->vat_type) : LangDropDownResource::make($this->vat_type()->first()),
            'vat_tax' =>  !$this->vat_type ? 0 : $this->vat_type()->first()->vat_tax,
            'amount' =>  $this->amount,
            'tax' =>   $this->tax,
            'tax_amount' =>  $this->total_amount,
            'tax_rate' =>  $this->rate,
            'tax_amount_rate' =>  $this->total_amount_rate,
            'status' =>  $this->status,
            'active' =>  $this->active,
        ];
    }
}
