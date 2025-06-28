<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\Casts\Decimal;
use App\Http\Model\AccVatDetail;
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
          'rate'=> Decimal::class,
          'payment_rate'=> Decimal::class,
          'active' => 'boolean',
      ];

      static public function vat_detail_payment_created_at_not_id($vat_detail_id,$created_at,$id) {
        $result = AccVatDetailPayment::where('vat_detail_id',$vat_detail_id )->where('created_at',">=",$created_at)->whereNot('id',$id)->get();
        return $result;
      }     

      static public function sum_vat_detail($vat_detail_id,$sum) {
        $result = AccVatDetailPayment::where('vat_detail_id',$vat_detail_id )->sum($sum);
        return $result;
      }  
      static public function sum_vat_detail_not_id($vat_detail_id,$sum,$id) {
        $result = AccVatDetailPayment::where('vat_detail_id',$vat_detail_id )->whereNot('id',$id)->sum($sum);
        return $result;
      }       

       public function vat_detail() {
        return $this->hasOne(AccVatDetail::class,'id','vat_detail_id');
    }   
      
}
