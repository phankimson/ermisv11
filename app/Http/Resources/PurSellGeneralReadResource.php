<?php

namespace App\Http\Resources;

use App\Http\Model\AccGeneral;
use App\Http\Model\Menu;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PurSellDetailReadResource;
use App\Http\Resources\ObjectDropDownListResource;

class PurSellGeneralReadResource extends JsonResource
{
    protected $customData;

    public function __construct($resource, $customData = [])
    {
        parent::__construct($resource);
        $this->customData = $customData;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $stock = $this->whenLoaded('detail')->first()->inventory->stock_receipt ? $this->whenLoaded('detail')->first()->inventory->stock_receipt : $this->whenLoaded('detail')->first()->inventory->stock_issue;
        if($stock){
            $stock_status = "1"; //Nhập vào tồn kho
        }else{
            $stock_status = "0"; //Không nhập vào tồn kho
        }
        if($this->tax){
            $invoice_status = "1"; //Có thuế
        }else{
            $invoice_status = "2"; //Không có thuế
        }
        $reference_data = AccGeneral::find_reference_by($this->id);
        $reference_type = Menu::find($reference_data->type);
        if($reference_type->code == $this->customData['key_cash']){
            $payment_method = "1";  //Tiền mặt
            $payment = "1";
        }else if($reference_type->code == $this->customData['key_bank']){
            $payment_method = "2";  //Tiền gửi
            $payment = "1";
        }else{
            $payment_method = "";  //Khác
            $payment = "0";
        }
        return [
            'id' => $this->id,
            'currency' => $this->currency,
            'voucher' => $this->voucher,
            'description' => $this->description,
            'voucher_date' => $this->voucher_date,
            'accounting_date' => $this->accounting_date,
            'traders' => $this->traders,
            'subject_id' => $this->subject,
            'rate' => $this->rate,
            'stock'=> $stock,
            'stock_status'=> $stock_status,
            'invoice_status'=> $invoice_status,
            'payment_method'=> $payment_method,
            'payment'=> $payment,
            'object' => $this->whenLoaded('object')?new ObjectDropDownListResource($this->whenLoaded('object')):new ObjectDropDownDefaultListResource(""),
            'total_quantity' => $this->total_quantity,
            'total_amount' => $this->total_amount,
            'total_amount_rate' =>  $this->total_amount_rate,
            'reference' =>  $this->reference,
            'reference_by' =>  $this->reference_by,
            'attach' =>  $this->whenLoaded('attach'),
            'status' =>  $this->status,
            'detail' => PurSellDetailReadResource::collection($this->whenLoaded('detail')),
            'tax' => TaxReadResource::collection($this->whenLoaded('tax')),
            'active' =>  $this->active,
        ];
    }

}

