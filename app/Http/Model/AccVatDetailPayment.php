<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\AccVatDetail;
use App\Http\Model\Casts\Decimal;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;

class AccVatDetailPayment extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';

      protected $table = 'acc_vat_detail_payment';

      public $incrementing = false; // and it doesn't even have to be auto-incrementing!

      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected $keyType = 'string';

      protected static function booted()
      {
          static::BootedBaseTrait();
      }

      protected $casts = [
          'payment'=> Decimal::class,
          'active' => 'boolean',
      ];
      
}
