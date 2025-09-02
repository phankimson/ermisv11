<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use Illuminate\Support\Facades\DB;

class AccBankAccount extends Model
{
      use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';

      protected $table = 'bank_account';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected static function booted()
  {
      static::BootedBaseTrait();
  }

      static public function get_raw() {
        $result = AccBankAccount::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','desc')->get();
        //$result = DB::select(DB::raw("SELECT t.* from (SELECT @i:=@i+1 as row_number, s.* FROM country s, (SELECT @i:=0) AS temp order by s.created_at asc) t order by t.row_number desc"));
        return $result;
      }

      static public function get_raw_skip_page($skip,$limit,$orderBy,$asc) {
        $result = AccBankAccount::WithRowNumberDb(env('CONNECTION_DB_ACC'),$orderBy,$asc)->orderBy('row_number','desc')->skip($skip)->take($limit)->get();  
        return $result;
      }

      static public function get_raw_skip_filter_page($skip,$limit,$orderBy,$asc,$filter) {
        $result = AccBankAccount::WithRowNumberWhereRawColumnDb(env('CONNECTION_DB_ACC'),$filter,$orderBy,$asc)->skip($skip)->take($limit)->get();  
        return $result;
      }

          
      static public function get_raw_export($select,$skip,$limit) {
        $result = AccBankAccount::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','asc')->skip($skip)->take($limit)
        ->leftJoin('bank as c', 't.bank_id', '=', 'c.id')
        ->leftJoin('account_systems as a', 'a.id', '=', 't.account_default')
        ->get(['row_number',DB::raw($select)]);
        //$result = DB::select(DB::raw("SELECT t.row_number,{$select} from (SELECT @i:=@i+1 as row_number, s.* FROM country s, (SELECT @i:=0) AS temp order by s.created_at asc) t order by t.row_number asc"));
        return $result;
      }

      static public function get_raw_balance_export($select,$skip,$limit,$period) {
        $result = AccBankAccount::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','asc')->skip($skip)->take($limit)
        ->leftJoin('bank as c', 't.bank_id', '=', 'c.id')
        ->leftJoin('bank_account_balance as s', 't.id', '=', 's.bank_account')
        ->leftJoin('account_systems as a', 'a.id', '=', 't.account_default')
        ->where('s.period', $period)
        ->get(['row_number',DB::raw($select)]);
        //$result = DB::select(DB::raw("SELECT t.row_number,{$select} from (SELECT @i:=@i+1 as row_number, s.* FROM country s, (SELECT @i:=0) AS temp order by s.created_at asc) t order by t.row_number asc"));
        return $result;
      }

      static public function get_with_balance_period($period) {
        $result = AccBankAccount::with(['balance' => function ($query) use ($period) {
            $query->where('period', $period);
        }])->orderBy('bank_account','desc')
        ->leftJoin('bank', 'bank_account.bank_id', '=', 'bank.id')
        ->leftJoin('account_systems', 'account_systems.id', '=', 'bank_account.account_default')->get(['bank_account.*','bank.name','account_systems.code']);
        //$result = DB::select(DB::raw("SELECT t.* from (SELECT @i:=@i+1 as row_number, s.* FROM country s, (SELECT @i:=0) AS temp order by s.created_at asc) t order by t.row_number desc"));
        return $result;
      }     

       public function balance()
    {
        return $this->hasMany(AccBankAccountBalance::class,'bank_account','id');
    }

      public function account()
    {
        return $this->hasOne(AccAccountSystems::class,'id','account_default');
    }
}
