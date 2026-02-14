<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;

class AccVatInfo extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';

      protected $table = 'acc_vat_info';

      public $incrementing = false; // and it doesn't even have to be auto-incrementing!

      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected $keyType = 'string';

      protected static function booted()
      {
          static::BootedBaseTrait();
      }

      static public function get_info($general_id) {
        $result = AccVatInfo::where('general_id',$general_id)->get();
        return $result;
      }
}
