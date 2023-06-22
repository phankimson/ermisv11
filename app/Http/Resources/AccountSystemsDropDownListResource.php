<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountSystemsDropDownListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      // Make sure current locale exists.
      $locale = $request->segment(1);
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $locale == "vi" ? $this->name :$this->name_en,
            'object'=> $this->detail_object,
            'bank_account' => $this->detail_bank_account,
            'work_code' => $this->detail_work,
            'cost_code' => $this->detail_cost,
            'case_code' => $this->detail_case,
            'statistical_code' => $this->detail_statistical,
            'order' => $this->detail_orders,
            'contract' => $this->detail_contract,
            'depreciation' => $this->detail_depreciation,
            'attribution' => $this->detail_attribution,
        ];
    }
}
