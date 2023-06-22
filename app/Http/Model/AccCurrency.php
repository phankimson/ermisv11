<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\AccDenominations;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use App\Http\Traits\OrderTraits;
use DB;

class AccCurrency extends Model
{
  use ScopesTraits,BootedTraits,OrderTraits;
      protected $connection = 'mysql2';
      protected $table = 'currency';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected static function booted()
  {
      static::BootedBaseTrait();
      static::OrderByCreatedAtBaseTrait();
  }


      static public function get_raw() {
        $result = AccCurrency::WithRowNumberDb('mysql2')->orderBy('row_number','desc')->get();       
        return $result;
      }

      static public function get_code($code) {
        $result = AccCurrency::where('code',$code)->first();       
        return $result;
      }

      static public function get_raw_export($select) {
        $result = AccCurrency::WithRowNumberDb('mysql2')->orderBy('row_number','asc')
        ->leftJoin('account_systems as a', 't.account_bank', '=', 'a.id')
        ->leftJoin('account_systems as b', 't.account_cash', '=', 'b.id')
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }


      public function denominations() {
        return $this->hasMany(AccDenominations::class,'currency_id','id');
      }
}
