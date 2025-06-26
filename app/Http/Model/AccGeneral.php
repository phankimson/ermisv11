<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\AccObject;
use App\Http\Model\AccDetail;
use App\Http\Model\AccVatDetailPayment;
use App\Http\Model\AccAttach;
use App\Http\Model\Casts\Decimal;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;

class AccGeneral extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';

      protected $table = 'acc_general';

      public $incrementing = false; // and it doesn't even have to be auto-incrementing!

      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected $keyType = 'string';

      protected static function booted()
      {
          static::BootedBaseTrait();
      }

      protected $casts = [
          'total_amount' => Decimal::class,
          'total_number' => Decimal::class,
          'total_discount'=> Decimal::class,
          'discount'=> Decimal::class,
          'discount_percent'=> Decimal::class,
          'rate'=> Decimal::class,
          'total_amount_rate'=> Decimal::class,
          'active' => 'boolean',
      ];

  //    public function getTotalAmountAttribute($value)
  //  {
  //      return number_format($value, $this->decimal , $this->decimal_symbol ,$this->decimal_symbol =='.' ? ',' : '.');
  //  }


      static public function get_range_date($end_date,$start_date) {
        $result = AccGeneral::whereBetween('accounting_date',[$end_date,$start_date])->orderBy('created_at', 'desc')->get();
        return $result;
      }

      static public function get_data_object($general_id){
        $result = AccGeneral::where('id',$general_id)->first()->load('object');
        return $result;
      }

      static public function get_data_load_all($general_id){
        $result = AccGeneral::where('id',$general_id)->first()->load('object','tax','attach','detail');
        return $result;
      }

      static public function get_data_load_vat_payment($general_id){
        $result = AccGeneral::where('id',$general_id)->first()->load('object','vat_detail_payment','vat_detail_payment.vat_detail');
        return $result;
      }

      static public function get_data_load_between($type,$startDate,$endDate){
        $result = AccGeneral::where('type',$type)->whereBetween('accounting_date',[$startDate,$endDate])->orderBy('accounting_date', 'asc')->get();
        return $result;
      }

      static public function get_data_load_between_reference($type,$startDate,$endDate,$reference_array){
        $result = AccGeneral::where('type',$type)->whereBetween('accounting_date',[$startDate,$endDate])->whereIn('reference_by',$reference_array)->get();
        return $result;
      }

      static public function get_id_with_detail($id,$arr_with) {
        $result = AccGeneral::where('id',$id)->with($arr_with)->first();
        return $result;
      }

      static public function get_reference_by($id) {
        $result = AccGeneral::where('reference_by',$id)->get();
        return $result;
      }

      static public function get_reference_by_whereNotIn($arr) {
          $result = AccGeneral::whereNotIn('id',$arr)->get();
          return $result;
      }

      static public function get_whereIn($arr) {
        $result = AccGeneral::whereIn('id',$arr)->get();
        return $result;
      }

      static public function find_subject($subject,$start_date,$end_date,) {
        $result = AccGeneral::where('subject',$subject)->whereBetween('voucher_date',[$end_date,$start_date])->first();
        return $result;
      }


      public function object()
    {
        return $this->belongsTo(AccObject::class,'subject','id');
    }

    public function detail() {
      return $this->hasMany(AccDetail::class,'general_id','id');
    }

    public function inventory() {
      return $this->hasMany(AccInventory::class,'general_id','id');
    }

    public function tax() {
      return $this->hasMany(AccVatDetail::class,'general_id','id');
    }

    public function vat_detail_payment() {
      return $this->hasMany(AccVatDetailPayment::class,'general_id','id');
    }

    public function attach() {
      return $this->hasMany(AccAttach::class,'general_id','id');
    }
}
