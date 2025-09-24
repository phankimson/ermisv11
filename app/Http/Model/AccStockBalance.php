<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use Illuminate\Support\Facades\DB;
use App\Http\Model\Casts\Decimal;

class AccStockBalance extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';
      protected $table = 'stock_balance';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected $casts = [
          'quantity_open'=> Decimal::class,
          'amount_open'=> Decimal::class,
          'quantity_receipt'=> Decimal::class,    
          'amount_receipt'=> Decimal::class,
          'quantity_issue'=> Decimal::class,
          'amount_issue'=> Decimal::class,
          'quantity_close'=> Decimal::class,
          'amount_close'=> Decimal::class,
      ];

      protected static function booted()
  {
      static::BootedBaseTrait();
  }


      static public function get_raw() {
        $result = AccStockBalance::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','desc')->get();       
        return $result;
      }

      static public function get_raw_export($select) {
        $result = AccStockBalance::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','asc')
        ->leftJoin('account_systems as a', 't.type_id', '=', 'a.id')
        ->leftJoin('period as b', 't.period', '=', 'b.id')
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }

      static public function get_item($period,$stock,$item) {
        $result = AccStockBalance::where('period',$period)->where('stock',$stock)->where('supplies_goods',$item)->first();
        return $result;
      }

      static public function get_supplies_goods($period,$supplies_goods,$stock) {
        $result = AccStockBalance::where('period',$period)->where('supplies_goods',$supplies_goods)->where('stock',$stock)->first();
        return $result;
      }

       static public function get_sum_supplies_goods($period,$supplies_goods) {
        $result = AccStockBalance::where('period',$period)->where('supplies_goods',$supplies_goods)->sum('amount_close');
        return $result;
      }

}
