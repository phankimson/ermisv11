<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountedFastDropDownListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      $type_object = $request->session()->get('type_object');
      return (object)[
          'id' => $this->id,
          'code' => $this->code,
          'name' => $this->name,
          'description' => $this->name?$this->name:$this->description,
          'debit' => $this->debit,
          'credit' => $this->credit,
          'subject_code' => $type_object == $type_object ? $this->subject_debit : $this->subject_credit,
          'case_code' => $this->case_code,
          'cost_code' => $this->cost_code,
          'statistical_code' => $this->statistical_code,
          'work_code' => $this->work_code,
          'department' => $this->department,
          'bank_account' => $this->bank_account,
          'accounted_fast' => $this->accounted_fast,
      ];
    }
}
