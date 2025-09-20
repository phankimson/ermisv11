<?php
namespace App\Http\Traits;
use App\Http\Model\AccCurrencyCheck;

trait CurrencyCheckTraits
{
      public function increaseCurrency($acc_debit,$currency,$amount,$rate,$bank_account=null)
      {
         $balance = AccCurrencyCheck::get_type_first($acc_debit,$currency,$bank_account);
            if($balance){
               $balance->amount = $balance->amount + ($amount * $rate);
               $balance->save();
            }else{
               $balance = new AccCurrencyCheck();
               $balance->type = $acc_debit;
               $balance->currency = $currency;
               $balance->bank_account = $bank_account;
               $balance->amount = ($amount * $rate);
               $balance->save();
            }
            return $balance;
      }   

    public function reduceCurrency($acc_credit,$currency,$amount,$rate,$bank_account=null)
    {
       $balance = AccCurrencyCheck::get_type_first($acc_credit,$currency,$bank_account);
          if($balance){
               $balance->amount = $balance->amount - ($amount * $rate);
               $balance->save();
          }else{
               $balance = new AccCurrencyCheck();
               $balance->type = $acc_credit;
               $balance->currency = $currency;
               $balance->bank_account = $bank_account;
               $balance->amount = 0 - ($amount * $rate);
               $balance->save();
          }
          return $balance;
    }    

}
