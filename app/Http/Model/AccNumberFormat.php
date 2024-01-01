<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

class AccNumberFormat extends Model
{
    use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';
      protected $table = 'number_format';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

          protected static function booted()
      {
          static::BootedBaseTrait();
      }

      static public function get_raw() {
        $result = AccNumberFormat::WithRowNumberDb('mysql2')->orderBy('row_number','desc')->get();       
        return $result;
      }

      static public function get_raw_export($select) {
        $result = AccNumberFormat::WithRowNumberDb('mysql2')->orderBy('row_number','asc')
        ->leftJoin('number_voucher as m', 't.number_voucher', '=', 'm.id')
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }

      static public function get_number_voucher_format($number_voucher,$day_format,$month_format,$year_format) {
        $result = AccNumberFormat::where('number_voucher',$number_voucher)->where('format',$format)->where('day',$day_format)->where('month',$month_format)->where('year',$year_format)->first();
        return $result;
      }

}
