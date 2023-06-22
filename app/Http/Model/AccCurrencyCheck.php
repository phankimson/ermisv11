<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

class AccCurrencyCheck extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';
      protected $table = 'currency_check';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected static function booted()
  {
      static::BootedBaseTrait();
  }


      static public function get_raw() {
        $result = AccCurrencyCheck::WithRowNumberDb('mysql2')->orderBy('row_number','desc')->get();       
        return $result;
      }

      static public function get_raw_export($select) {
        $result = AccCurrencyCheck::WithRowNumberDb('mysql2')->orderBy('row_number','asc')
        ->leftJoin('currency as a', 't.currency', '=', 'a.id')
        ->leftJoin('bank_account as b', 't.bank_account', '=', 'b.id')
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }

      static public function get_type_first($type,$currency,$bank_account) {
        $result = AccCurrencyCheck::where('type',$type)->where('currency',$currency)->where('bank_account',$bank_account)->first();
        return $result;
      }

}
