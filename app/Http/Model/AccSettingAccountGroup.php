<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

class AccSettingAccountGroup extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';
      protected $table = 'setting_account_group';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on
      protected $keyType = 'string';

      protected static function booted()
      {
          static::BootedBaseTrait();
      }

      static public function get_code($code) {
        $result = AccSettingAccountGroup::where('code',$code)->with('account_filter')->first();
        return $result;
      }

      static public function get_raw() {
        $result = AccSettingAccountGroup::WithRowNumberDb('mysql2')->orderBy('row_number','desc')->with('account_filter')->get()->pluckDistant('account_filter', 'account_systems');       
        return $result;
      }

      static public function get_raw_export($select) {
        $result =  AccSettingAccountGroup::WithRowNumberDb('mysql2')->orderBy('row_number','asc')->get(['row_number',DB::raw($select)]);        
        return $result;
      } 


      public function account_filter()
      {
          return $this->morphMany(AccAccountSystemsFilter::class, 'account_systems_filter');
      }


}
