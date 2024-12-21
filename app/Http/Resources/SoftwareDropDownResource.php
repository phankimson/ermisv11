<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SoftwareDropDownResource extends JsonResource
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
      return (object)[
          'value' => $this->id,
          'text' => $this->url ." - ".($locale == "vi" ? $this->name :$this->name_en),
      ];
    }
}
