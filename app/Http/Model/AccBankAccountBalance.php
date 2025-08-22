<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use Illuminate\Support\Facades\DB;
use App\Http\Model\Casts\Decimal;

class AccBankAccountBalance extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';
      protected $table = 'bank_account_balance';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected static function booted()
  {
      static::BootedBaseTrait();
  }

    protected $casts = [
          'debit_open'=> Decimal::class,
          'credit_open'=> Decimal::class,
          'debit'=> Decimal::class,    
          'credit'=> Decimal::class,
          'debit_close'=> Decimal::class,
          'credit_close'=> Decimal::class,
      ];

    

      static public function get_raw() {
        $result = AccBankAccountBalance::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','desc')->get();       
        return $result;
      }

      static public function get_raw_export($select) {
        $result = AccBankAccountBalance::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','asc')
        ->leftJoin('account_systems as a', 't.type_id', '=', 'a.id')
        ->leftJoin('period as b', 't.period', '=', 'b.id')
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }

      static public function get_bank_account($period,$bank_account) {
        $result = AccBankAccountBalance::where('period',$period)->where('bank_account',$bank_account)->first();
        return $result;
      }

}
