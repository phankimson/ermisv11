<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\AccVat;
use App\Http\Model\AccVatDetailPayment;
use App\Http\Model\Casts\Decimal;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;

class AccVatDetail extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';

      protected $table = 'acc_vat_detail';

      public $incrementing = false; // and it doesn't even have to be auto-incrementing!

      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected $keyType = 'string';

      protected $with = ['vat_type'];

      protected static function booted()
      {
          static::BootedBaseTrait();
      }

      protected $casts = [
          'amount'=> Decimal::class,
          'total_amount'=> Decimal::class,
          'rate'=> Decimal::class,    
          'total_amount_rate'=> Decimal::class,
          'active' => 'boolean',
      ];

      static public function get_detail($general_id) {
        $result = AccVatDetail::where('general_id',$general_id)->get();
        return $result;
      }
      static public function get_detail_whereNotIn_delete($general_id,$arr) {
        AccVatDetail::where('general_id',$general_id)->whereNotIn('id',$arr)->delete();
      }

      static public function get_invoice($arr){
         $result = AccVatDetail::where($arr)->first();
         return $result;
      }

      static public function get_detail_subject($subject_id,$end_date,$start_date,$invoice_type,$payment = null) {
        $result = AccVatDetail::where('subject_id',$subject_id)->where('invoice_type',$invoice_type)->withSum('vat_detail_payment as vat_detail_payment', 'payment')->whereBetween('date_invoice',[$end_date,$start_date])->whereNot('payment',$payment)->get();
        return $result;
      }

      public function vat_type() {
        return $this->belongsTo(AccVat::class,'vat_type','id');
      }

      public function vat_detail_payment() {
        return $this->hasMany(AccVatDetailPayment::class,'vat_detail_id','id');
      }
}
