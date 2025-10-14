<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use App\Http\Model\Casts\Decimal;
use Illuminate\Support\Facades\DB;

class AccStockCheck extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';
      protected $table = 'stock_check';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on


        protected $casts = [
       'quantity' => Decimal::class,
    ];
      
      protected static function booted()
  {
      static::BootedBaseTrait();
  }


      static public function get_raw() {
        $result = AccStockCheck::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','desc')->get();       
        return $result;
      }

      static public function get_raw_export($select) {
        $result = AccStockCheck::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','asc')
        ->leftJoin('stock as a', 't.stock', '=', 'a.id')
        ->leftJoin('supplies_goods as b', 't.supplies_goods', '=', 'b.id')
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }

      static public function get_type_first($type,$stock,$supplies_goods) {
        $result = AccStockCheck::where('type',$type)->where('stock',$stock)->where('supplies_goods',$supplies_goods)->first();
        return $result;
      }

}
