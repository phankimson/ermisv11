<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LangDropDownListResource;
use App\Http\Resources\BankAccountDropDownListResource;

class CashReceiptDetailResource extends JsonResource
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
            'currency' => $this->currency,
            'debit' => !$this->debit ? DefaultDropDownListResource::make($this->debit) : LangDropDownListResource::make($this->debit()->first()),
            'credit' => !$this->credit ? DefaultDropDownListResource::make($this->debit) : LangDropDownListResource::make($this->credit()->first()),
            'amount' => $this->amount,
            'rate' => $this->rate,
            'amount_rate' => $this->amount_rate,
            'lot_number' => $this->lot_number,
            'expiry_date' =>  $this->expiry_date,
            'order' =>  $this->order,
            'contract' =>  $this->contract,
            'subject_id' =>  $this->subject_id_credit,
            'subject_code' =>  !$this->subject_id_credit ? DefaultDropDownListResource::make($this->subject_id_credit) : LangDropDownListResource::make($this->subject_credit()->first()),
            'subject_name' =>  $this->subject_name_credit,
            'case_code' =>  !$this->case_code ? DefaultDropDownListResource::make($this->case_code) : LangDropDownListResource::make($this->case_code()->first()),
            'cost_code' =>  !$this->cost_code ? DefaultDropDownListResource::make($this->cost_code) : LangDropDownListResource::make($this->cost_code()->first()),
            'statistical_code' => !$this->statistical_code ? DefaultDropDownListResource::make($this->statistical_code) : LangDropDownListResource::make($this->statistical_code()->first()),
            'work_code' =>  !$this->work_code ? DefaultDropDownListResource::make($this->work_code) : LangDropDownListResource::make($this->work_code()->first()),
            'accounted_fast' => !$this->accounted_fast ? DefaultDropDownListResource::make($this->accounted_fast) :  LangDropDownListResource::make($this->accounted_fast()->first()),
            'department' =>  !$this->department ? DefaultDropDownListResource::make($this->department) : LangDropDownListResource::make($this->department()->first()),
            'bank_account' =>  !$this->bank_account ? DefaultDropDownListResource::make($this->bank_account) : BankAccountDropDownListResource::make($this->bank_account()->first()),
            'status' =>  $this->status,
            'active' =>  $this->active,
        ];
    }
}
