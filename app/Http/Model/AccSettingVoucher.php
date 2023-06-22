<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

class AccSettingVoucher extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';
      protected $table = 'setting_voucher';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on
      protected $keyType = 'string';

          protected static function booted()
      {
          static::BootedBaseTrait();
      }

      static public function get_raw() {
        $result = AccSettingVoucher::WithRowNumberDb('mysql2')->orderBy('row_number','desc')->with('debit_filter','credit_filter')->get()->pluckDistant('debit_filter','account_systems')->pluckDistant('credit_filter','account_systems');       
        return $result;
      }

      static public function get_raw_export($select) {
        $env = env("DB_DATABASE");
        $result = AccSettingVoucher::WithRowNumberDb('mysql2')->orderBy('row_number','asc')
        ->leftJoin('account_systems as a', 't.debit', '=', 'a.id')
        ->leftJoin('account_systems as b', 't.credit', '=', 'b.id')
        ->leftJoin($env.'.menu as c', 't.menu_id', '=', 'c.id')
        ->leftJoin('account_systems as d', 't.vat_account', '=', 'd.id')
        ->leftJoin('account_systems as e', 't.discount_account', '=', 'e.id') 
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }

      static public function get_menu($menu) {
        $result = AccSettingVoucher::where('menu_id',$menu)->with('debit_filter','credit_filter')->first();
        $result->debit_filter = $result->debit_filter->pluck('account_systems');
        $result->credit_filter = $result->credit_filter->pluck('account_systems');
        return $result;
      }

        public function debit_filter()
      {
          return $this->hasMany(AccAccountSystemsFilter::class, 'account_systems_filter_id' ,'id')->where('account_systems_filter_type', '=', 'AccSettingVoucherDebit');
      }

      public function credit_filter()
      {
          return $this->hasMany(AccAccountSystemsFilter::class, 'account_systems_filter_id' ,'id')->where('account_systems_filter_type', '=', 'AccSettingVoucherCredit');
      }

}
