<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\Scopes\OrderByCreatedAtScope;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

class Systems extends Model
{
      use ScopesTraits,BootedTraits;
      protected $table = 'systems';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on
      protected $casts = [
        'id' => 'string'
      ];

      protected static function booted()
      {
      static::BootedBaseTrait();
      }



      static public function get_raw() {
      $result = Systems::WithRowNumber()->orderBy('row_number','desc')->get();
      return $result;
      }

      static public function get_raw_export($select) {
        $result =  Systems::WithRowNumber()->orderBy('row_number','asc')->get(['row_number',DB::raw($select)]);
        return $result;
      }

      static public function get_systems($code) {
        $result = Systems::where('code',$code)->where('active',1)->first();
        return $result;
      }

      static public function get_systems_whereIn($arr) {
        $result = Systems::whereIn('code',$arr)->where('active',1)->get();
        return $result;
      }
}
