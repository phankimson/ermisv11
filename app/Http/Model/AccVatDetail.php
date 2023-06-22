<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\AccVat;
use App\Http\Model\Casts\Decimal;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

class AccVatDetail extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';

      protected $table = 'acc_vat_detail';

      public $incrementing = false; // and it doesn't even have to be auto-incrementing!

      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected $keyType = 'string';

      protected $with = ['tax'];

      protected static function booted()
      {
          static::BootedBaseTrait();
      }

      protected $casts = [
          'amount'=> Decimal::class,
          'total_amount'=> Decimal::class,
          'active' => 'boolean',
      ];

      static public function get_detail($general_id) {
        $result = AccVatDetail::where('general_id',$general_id)->get();
        return $result;
      }
      static public function get_detail_whereNotIn_delete($general_id,$arr) {
        AccVatDetail::where('general_id',$general_id)->whereNotIn('id',$arr)->delete();
      }

      public function tax() {
        return $this->belongsTo(AccVat::class,'tax','id');
      }
}
