<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ObjectTypeDropDownResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    
    public function toArray($request)
    {
        $locale = $request->segment(1);
      // Make sure current locale exists.
        return (object)[
            'value' => $this->id,
            'text' =>  $this->filter ." - ".$this->code. " - ". ($locale == "vi" ? $this->name :$this->name_en),
        ];
    }
}
