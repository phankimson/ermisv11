<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LangDropDownResource;
use App\Http\Resources\DefaultDropDownResource;

class InventoryIssueDetailReadResource extends JsonResource
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
            'item_id' => $this->item_id,
            'item_code' => !$this->item_id ? DefaultDropDownResource::make("") : ObjectDropDownResource::make($this->item()->first()),
            'item_name' => $this->item_name,
            'currency' => $this->currency,
            'debit' => LangDropDownResource::make($this->debit()->first()),
            'credit' => LangDropDownResource::make($this->credit()->first()),
            'quantity' => $this->quantity,
            'price' => $this->price,
            'amount' => $this->amount,
            'rate' => $this->rate,
            'amount_rate' => $this->amount_rate,
            'lot_number' => $this->lot_number,
            'expiry_date' =>  $this->expiry_date,
            'order' =>  $this->order,
            'contract' =>  $this->contract,
            'subject_id' =>  $this->subject_id_debit,
            'subject_code' =>  !$this->subject_id_debit ? DefaultDropDownResource::make("") : ObjectDropDownResource::make($this->subject_debit()->first()),
            'subject_name' =>  $this->subject_name_debit,
            'case_code' =>  !$this->case_code ? DefaultDropDownResource::make("") : LangDropDownResource::make($this->case_code()->first()),
            'cost_code' =>  !$this->cost_code ? DefaultDropDownResource::make("") : LangDropDownResource::make($this->cost_code()->first()),
            'statistical_code' => !$this->statistical_code ? DefaultDropDownResource::make("") : LangDropDownResource::make($this->statistical_code()->first()),
            'work_code' =>  !$this->work_code ? DefaultDropDownResource::make("") : LangDropDownResource::make($this->work_code()->first()),
            'accounted_fast' => !$this->accounted_fast ? DefaultDropDownResource::make("") : LangDropDownResource::make($this->accounted_fast()->first()),
            'department' =>  !$this->department ? DefaultDropDownResource::make("") : LangDropDownResource::make($this->department()->first()),            
            'status' =>  $this->status,
            'active' =>  $this->active,
        ];
    }
}
