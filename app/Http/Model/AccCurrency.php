<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\AccDenominations;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use App\Http\Traits\OrderTraits;
use Illuminate\Support\Facades\DB;

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
      //static::OrderByCreatedAtBaseTrait();
  }


      static public function get_raw() {
        $result = AccCurrency::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','desc')->with('denominations')->get();       
        return $result;
      }

      static public function get_raw_skip_page($skip,$limit,$orderBy,$asc) {
        $result = AccCurrency::WithRowNumberDb(env('CONNECTION_DB_ACC'),$orderBy,$asc)->skip($skip)->take($limit)->with('denominations')->get();  
        return $result;
      }

      static public function get_raw_skip_filter_page($skip,$limit,$orderBy,$asc,$filter) {
        $result = AccCurrency::WithRowNumberWhereRawColumnDb(env('CONNECTION_DB_ACC'),$filter,$orderBy,$asc)->skip($skip)->take($limit)->with('denominations')->get();  
        return $result;
      }

      static public function get_code($code) {
        $result = AccCurrency::where('code',$code)->first();       
        return $result;
      }

      static public function get_raw_export($select,$skip,$limit) {
        $result = AccCurrency::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','asc')->skip($skip)->take($limit)
        ->leftJoin('account_systems as a', 't.account_bank', '=', 'a.id')
        ->leftJoin('account_systems as b', 't.account_cash', '=', 'b.id')
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }


      public function denominations() {
        return $this->hasMany(AccDenominations::class,'currency_id','id');
      }
}
