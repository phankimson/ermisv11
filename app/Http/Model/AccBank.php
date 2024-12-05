<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

class AccBank extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';

      protected $table = 'bank';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected static function booted()
      {
          static::BootedBaseTrait();
      }

      
      static public function get_raw() {
        $result = AccBank::WithRowNumberDb('mysql2')->orderBy('row_number','desc')->get();       
        return $result;
      }

      static public function get_raw_skip_page($skip,$limit,$orderBy,$asc) {
        $result = AccBank::WithRowNumberDb('mysql2',$orderBy,$asc)->skip($skip)->take($limit)->get();  
        return $result;
      }

      static public function get_raw_skip_filter_page($skip,$limit,$orderBy,$asc,$filter) {
        $result = AccBank::WithRowNumberWhereRawColumnDb('mysql2',$filter,$orderBy,$asc)->skip($skip)->take($limit)->get();  
        return $result;
      }


      static public function get_raw_export($select,$skip,$limit) {
        $result =  AccBank::WithRowNumberDb('mysql2')->orderBy('row_number','asc')->skip($skip)->take($limit)->get(['row_number',DB::raw($select)]);        
        return $result;
      } 
}
