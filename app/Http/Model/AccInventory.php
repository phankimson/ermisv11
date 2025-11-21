<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\Casts\Decimal;
use App\Http\Model\Casts\Date;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;


class AccInventory extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';

      protected $table = 'acc_inventory';

      public $incrementing = false; // and it doesn't even have to be auto-incrementing!

      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected $with = ['unit_item','stock_receipt_item','stock_issue_item'];
      
      protected $keyType = 'string';

      protected static function booted()
      {
          static::BootedBaseTrait();
      }

      protected $casts = [
          'quantity' => Decimal::class,
          'quantity_receipt' => Decimal::class,
          'price'=> Decimal::class,
          'amount'=> Decimal::class,
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

       static public function get_detail_id_whereNotIn_delete($general_id,$arr) {
        AccInventory::where('general_id',$general_id)->whereNotIn('detail_id',$arr)->delete();
      }

      static public function get_detail_first($id) {
        $result = AccInventory::where('detail_id',$id)->first();
        return $result;
      }

      public function detail() {
      return $this->hasOne(AccDetail::class,'id','detail_id');
      }

      public function unit_item() {
        return $this->belongsTo(AccUnit::class,'unit','id');
      }

      public function stock_receipt_item() {
        return $this->belongsTo(AccStock::class,'stock_receipt','id');
      }

       public function stock_issue_item() {
        return $this->belongsTo(AccStock::class,'stock_issue','id');
      }
} 
