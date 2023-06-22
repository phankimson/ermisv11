<?php

namespace App\Http\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use App\Http\Model\Casts\Decimal;

use Illuminate\Database\Eloquent\Model;

class AccSuppliesGoodsDiscount extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';

      protected $table = 'supplies_goods_discount';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected $casts = [
        'quantity_start' => Decimal::class,
        'quantity_end' => Decimal::class,
        'amount_discount'=> Decimal::class,
        'percent_discount'=> Decimal::class,
        'active' => 'boolean',
    ];

      protected static function booted()
      {
          static::BootedBaseTrait();
      }

  static public function get_discount($supplies_goods) {
    $result = AccSuppliesGoodsDiscount::where('supplies_goods_id',$supplies_goods)->get();
    return $result;
  }


}
