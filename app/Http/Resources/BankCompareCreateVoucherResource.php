<?php

namespace App\Http\Resources;

use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccBankAccount;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LangDropDownResource;
use App\Http\Resources\ObjectDropDownResource;
use App\Http\Resources\DefaultDropDownResource;

class BankCompareCreateVoucherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
     protected $customData;

     public function customData($value){
        $this->customData = $value;
         return $this;
    }    

    public function toArray($request)
    {
        $account_debit = AccAccountSystems::find($this->customData['setting_voucher']->debit);
        $account_credit = AccAccountSystems::find($this->customData['setting_voucher']->credit);
        $bank_account = AccBankAccount::find($this->bank_account);
        return [
            'id' => "",
            'description' => $this->transaction_description,
            'currency' => $this->customData['rate']->id,
            'debit' =>  $account_debit == null ? DefaultDropDownResource::make("") : LangDropDownResource::make($account_debit),
            'credit' => $account_credit == null ? DefaultDropDownResource::make("") : LangDropDownResource::make($account_credit),
            'amount' => $this->debit_amount == 0 ? $this->credit_amount : $this->debit_amount,
            'rate' => $this->customData['rate']->rate,
            'amount_rate' => $this->debit_amount == 0 ? $this->credit_amount * $this->customData['rate']->rate : $this->debit_amount * $this->customData['rate']->rate,
            'lot_number' => "",
            'expiry_date' =>  "",
            'order' =>  "",
            'contract' =>  "",
            'subject_id' =>  $this->customData['object'] != null ?$this->customData['object']->id:"",
            'subject_code' =>  $this->customData['object'] != null ? ObjectDropDownResource::make($this->customData['object']):DefaultDropDownResource::make(""),
            'subject_name' =>  $this->corresponsive_name,
            'case_code' =>  DefaultDropDownResource::make(""),
            'cost_code' =>  DefaultDropDownResource::make(""),
            'statistical_code' => DefaultDropDownResource::make(""),
            'work_code' =>  DefaultDropDownResource::make(""),
            'accounted_fast' => DefaultDropDownResource::make(""),
            'department' =>  DefaultDropDownResource::make(""),
            'bank_account' =>  $bank_account == null ? DefaultDropDownResource::make("") : BankDropDownResource::make($bank_account),
        ];
    }

    public static function collection($resource){
        return new BankCompareCreateVoucherResource($resource);
    }
}
