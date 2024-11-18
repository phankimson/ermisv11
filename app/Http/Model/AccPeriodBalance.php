<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

class AccPeriodBalance extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';
      protected $table = 'period_balance';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected static function booted()
  {
      static::BootedBaseTrait();
  }


      static public function get_raw() {
        $result = AccPeriodBalance::WithRowNumberDb('mysql2')->orderBy('row_number','desc')->get();       
        return $result;
      }

      static public function get_raw_export($select) {
        $result = AccPeriodBalance::WithRowNumberDb('mysql2')->orderBy('row_number','asc')
        ->leftJoin('object as a', 't.type_id', '=', 'a.id')
        ->leftJoin('account_systems as b', 't.type_id', '=', 'b.id')
        ->leftJoin('supplies_goods as c', 't.type_id', '=', 'c.id')
        ->leftJoin('period as d', 'd.period', '=', 'd.id')
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }

      static public function get_type_first($type,$type_id,$period) {
        $result = AccPeriodBalance::where('type',$type)->where('type_id',$type_id)->where('period',$period)->first();
        return $result;
      }

}
