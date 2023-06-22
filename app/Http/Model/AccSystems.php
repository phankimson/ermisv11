<?php

namespace App\Http\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

use Illuminate\Database\Eloquent\Model;

class AccSystems extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';
      protected $table = 'systems';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected static function booted()
      {
          static::BootedBaseTrait();
      }

      static public function get_raw() {
        $result = AccSystems::WithRowNumberDb('mysql2')->orderBy('row_number','desc')->get();       
        return $result;
      }

      static public function get_raw_export($select) {
        $result =  AccSystems::WithRowNumberDb('mysql2')->orderBy('row_number','asc')->get(['row_number',DB::raw($select)]);        
        return $result;
      } 

      static public function get_systems($code) {
        $result = AccSystems::where('code',$code)->where('active',1)->first();
        return $result;
      }
      static public function get_systems_like($code) {
        $result = AccSystems::where('code','like',$code)->where('active',1)->get();
        return $result;
      }
      static public function get_systems_whereIn($arr) {
        $result = AccSystems::whereIn('code',$arr)->where('active',1)->get();
        return $result;
      }
}
