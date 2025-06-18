<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Model\AccObject;

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
      $subject_code = $type_object == $type_object ? $this->subject_debit : $this->subject_credit;
      $subject_name = AccObject::find($subject_code);
      return (object)[
          'id' => $this->id,
          'code' => $this->code,
          'name' => $this->name,
          'description' => $this->name?$this->name:$this->description,
          'debit' => $this->debit,
          'credit' => $this->credit,
          'subject_code' => $subject_code,
          'subject_name'=>$subject_name ? $subject_name->name : "",
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
