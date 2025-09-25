<?php
namespace App\Http\Traits;
use App\Http\Model\AccStockCheck;

trait StockCheckTraits
{
      public function increaseStock($acc_debit,$stock,$supplies_goods,$quantity)
      {
         $balance = AccStockCheck::get_type_first($acc_debit,$stock,$supplies_goods);
            if($balance){
               $balance->quantity = $balance->quantity + $quantity ;
               $balance->save();
            }else{
               $balance = new AccStockCheck();
               $balance->type = $acc_debit;
               $balance->stock = $stock;
               $balance->supplies_goods = $supplies_goods;
               $balance->quantity = $quantity;
               $balance->save();
            }
            return $balance;
      }   

    public function reduceStock($acc_credit,$stock,$supplies_goods,$quantity)
    {
       $balance = AccStockCheck::get_type_first($acc_credit,$stock,$supplies_goods);
          if($balance){
               $balance->quantity = $balance->quantity - $quantity ;
               $balance->save();
          }else{
               $balance = new AccStockCheck();
               $balance->type = $acc_credit;
               $balance->stock = $stock;
               $balance->supplies_goods = $supplies_goods;
               $balance->quantity = 0 - $quantity ;
               $balance->save();
          }
          return $balance;
    }     

}
