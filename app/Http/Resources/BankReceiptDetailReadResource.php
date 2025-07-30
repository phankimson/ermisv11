<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LangDropDownResource;
use App\Http\Resources\BankDropDownResource;
use App\Http\Resources\DefaultDropDownResource;

class BankReceiptDetailReadResource extends JsonResource
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
            'debit' => LangDropDownResource::make($this->debit()->first()),
            'credit' => LangDropDownResource::make($this->credit()->first()),
            'amount' => $this->amount,
            'rate' => $this->rate,
            'amount_rate' => $this->amount_rate,
            'lot_number' => $this->lot_number,
            'expiry_date' =>  $this->expiry_date,
            'order' =>  $this->order,
            'contract' =>  $this->contract,
            'subject_id' =>  $this->subject_id_credit,
            'subject_code' =>  !$this->subject_id_credit ? DefaultDropDownResource::make($this->subject_id_credit) : ObjectDropDownResource::make($this->subject_credit()->first()),
            'subject_name' =>  $this->subject_name_credit,
            'case_code' =>  !$this->case_code ? DefaultDropDownResource::make($this->case_code) : LangDropDownResource::make($this->case_code()->first()),
            'cost_code' =>  !$this->cost_code ? DefaultDropDownResource::make($this->cost_code) : LangDropDownResource::make($this->cost_code()->first()),
            'statistical_code' => !$this->statistical_code ? DefaultDropDownResource::make($this->statistical_code) : LangDropDownResource::make($this->statistical_code()->first()),
            'work_code' =>  !$this->work_code ? DefaultDropDownResource::make($this->work_code) : LangDropDownResource::make($this->work_code()->first()),
            'accounted_fast' => !$this->accounted_fast ? DefaultDropDownResource::make($this->accounted_fast) : LangDropDownResource::make($this->accounted_fast()->first()),
            'department' =>  !$this->department ? DefaultDropDownResource::make($this->department) : LangDropDownResource::make($this->department()->first()),
            'bank_account' =>  !$this->bank_account_debit ? DefaultDropDownResource::make($this->bank_account_debit) : BankDropDownResource::make($this->bank_account_debit()->first()),
            'status' =>  $this->status,
            'active' =>  $this->active,
        ];
    }
}
