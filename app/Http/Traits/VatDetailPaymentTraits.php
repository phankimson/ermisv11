<?php
namespace App\Http\Traits;
use App\Http\Model\AccVatDetail;
use App\Http\Model\AccVatDetailPayment;

trait VatDetailPaymentTraits
{
      public function updateStatusPayment($tax_payment)
    {
               foreach($tax_payment as $v){
                    $p = AccVatDetail::find($v->vat_detail_id);
                    if($p){
                         $p->payment = 0;
                         $p->save(); 
                    }
                    // Update lại số tiền đã thanh toán của từng phiếu
                    $tax_payment_update = AccVatDetailPayment::vat_detail_payment_created_at_not_id($v->vat_detail_id,$v->created_at,$v->id);
                    foreach($tax_payment_update as $t){
                    if($t->paid > $v->paid){
                         $t->paid = $t->paid - $v->payment;
                         $t->remaining = $t->remaining + $v->payment;
                         $t->save();
                    }          
                    }               
               }; 
    }

    
}
