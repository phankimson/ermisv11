<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ObjectOpenBalanceResource extends JsonResource
{
    private static $data;
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
            'balance_id' => $this->balance->count()>0 ? $this->balance->first()->id : "",
            'code' => $this->code,
            'name' => $this->name,
            'account_default' => $this->account_default?$this->account_default:self::$data,
            'debit_balance' => $this->balance->count()>0? $this->balance->first()->debit_close : 0,
            'credit_balance' => $this->balance->count()>0 ? $this->balance->first()->credit_close : 0,
        ];
      
    } 
    
     //I made custom function that returns collection type
  public static function customCollection($resource, $data): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
  {
   //you can add as many params as you want.
    self::$data = $data;
    return parent::collection($resource);
  }

}
