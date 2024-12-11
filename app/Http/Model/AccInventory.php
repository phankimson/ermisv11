<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\Casts\Decimal;
use App\Http\Model\Casts\Date;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use Illuminate\Support\Facades\DB;

class AccInventory extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';

      protected $table = 'acc_inventory';

      public $incrementing = false; // and it doesn't even have to be auto-incrementing!

      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on
      
      protected $keyType = 'string';

      protected static function booted()
      {
          static::BootedBaseTrait();
      }

      protected $casts = [
          'quantity' => Decimal::class,
          'quantity_receipt' => Decimal::class,
          'price'=> Decimal::class,
          'purchase_price'=> Decimal::class,
          'amount'=> Decimal::class,
          'purchase_amount'=> Decimal::class,
          'expiry_date' => Date::class,
          'active' => 'boolean',
      ];

      static public function get_detail($general_id) {
        $result = AccInventory::where('general_id',$general_id)->get();
        return $result;
      }

      static public function get_detail_active($general_id,$active) {
        $result = AccInventory::where('general_id',$general_id)->where('active', $active)->get();
        return $result;
      }

      static public function get_detail_whereNotIn_delete($general_id,$arr) {
        AccInventory::where('general_id',$general_id)->whereNotIn('id',$arr)->delete();
      }
    
}
