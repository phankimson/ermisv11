<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
//use App\Http\Traits\OrderTraits;
use DB;

class AccObjectType extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';
      protected $table = 'object_type';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected static function booted()
      {
          static::BootedBaseTrait();
      }
   
      static public function get_raw() {
        $result = AccObjectType::WithRowNumberDb('mysql2')->orderBy('row_number','desc')->get();       
        return $result;
      }

      static public function get_raw_export($select) {
        $result =  AccObjectType::WithRowNumberDb('mysql2')->orderBy('row_number','asc')->get(['row_number',DB::raw($select)]);        
        return $result;
      } 

      static public function get_filter($filter) {
        $result = AccObjectType::where('filter',$filter)->first();       
        return $result;
      }
}
