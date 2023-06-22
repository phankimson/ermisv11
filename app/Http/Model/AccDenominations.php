<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use App\Http\Traits\OrderTraits;

class AccDenominations extends Model
{
  use ScopesTraits,BootedTraits,OrderTraits;

      protected $connection = 'mysql2';
      protected $table = 'denominations';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected static function booted()
  {
      static::BootedBaseTrait();
      static::OrderByCreatedAtBaseTrait();
  }

  static public function get_currency($currency) {
    $result = AccDenominations::where('currency_id',$currency)->get();
    return $result;
  }


}
