<?php

namespace App\Http\Resources;

use App\Http\Model\AccObject;
use Illuminate\Http\Resources\Json\JsonResource;

class BankCompareCreateGeneralVoucherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

   protected $customData;

    public function __construct($resource, $customData = [])
    {
        parent::__construct($resource);
        $this->customData = $customData;
    }

    public function toArray($request)
    {      
        $object = $this->corresponsive_name?AccObject::get_name($this->corresponsive_name):null;    
        return [
            'id'=>"",
            'voucher'=>"",
            'currency' => $this->customData['rate']->id,
            'rate' => $this->customData['rate']->rate,   
            'description' => $this->transaction_description,
            'subject_id' => $object?$object->id:"",
            'object' => $object?new ObjectDropDownListResource($object):new ObjectDropDownDefaultListResource(""),
            'voucher_date' => date('Y-m-d', strtotime($this->accounting_date)),
            'accounting_date' => date('Y-m-d', strtotime($this->accounting_date)),
            'bank_account'=> $this->bank_account,
            'traders' => $this->corresponsive_account." - ".$this->corresponsive_name,
            'total_amount' => $this->debit_amount == 0 ? $this->credit_amount : $this->debit_amount,
            'total_amount_rate' => $this->debit_amount == 0 ? $this->credit_amount * $this->customData['rate']->rate : $this->debit_amount *$this->customData['rate']->rate,      
            'detail' => BankCompareCreateVoucherResource::collection($this)->customData(['rate' =>$this->customData['rate'],'setting_voucher'=>$this->customData['setting_voucher'],'object'=>$object]),  
            'tax'=>[],
        ];
    }
}
