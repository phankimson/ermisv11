<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckSubjectResource extends JsonResource
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
            'subject_tax_code' => $this['data']['id'],
            'subject_name' => $this['data']['name'],
            'subject_name_en' => $this['data']['internationalName'],
            'subject_address' => $this['data']['address'],
            'subject_active' => $this['data']['status'] == "NNT đang hoạt động" ? true : false,            
        ];
    }
}
