<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use App\Http\Traits\OrderTraits;
use App\Http\Model\Casts\Decimal;

class AccBankCompare extends Model
{
  use ScopesTraits,BootedTraits,OrderTraits;

      protected $connection = 'mysql2';
      protected $table = 'bank_compare';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

       protected $casts = [
          'debit_amount'=> Decimal::class,   
          'credit_amount'=> Decimal::class,
      ];

      protected static function booted()
  {
      static::BootedBaseTrait();
      static::OrderByCreatedAtBaseTrait();
  }
     static public function get_data_load_between($bank,$startDate,$endDate){
        $result = AccBankCompare::where('bank_account',$bank)->whereBetween('accounting_date',[$startDate,$endDate])->orderBy('accounting_date', 'asc')->orderBy('created_at', 'asc')->get();
        return $result;
    }

     static public function get_item_object($id){
        $result = AccBankCompare::where('id',$id)->leftJoin('bank_account', function ($join) {
        $join->on('bank_account.id', '=', 'bank_compare.bank_account');})->first();
        return $result;
    }

}
