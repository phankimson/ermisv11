<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use Illuminate\Support\Facades\DB;

class AccStock extends Model
{
      use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';
      protected $table = 'stock';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected static function booted()
      {
          static::BootedBaseTrait();
      }
   
      static public function get_raw() {
        $result = AccStock::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','desc')->get();       
        return $result;
      }

      static public function get_raw_skip_page($skip,$limit,$orderBy,$asc) {
        $result = AccStock::WithRowNumberDb(env('CONNECTION_DB_ACC'),$orderBy,$asc)->skip($skip)->take($limit)->get();  
        return $result;
      }

      static public function get_raw_skip_filter_page($skip,$limit,$orderBy,$asc,$filter) {
        $result = AccStock::WithRowNumberWhereRawColumnDb(env('CONNECTION_DB_ACC'),$filter,$orderBy,$asc)->skip($skip)->take($limit)->get();  
        return $result;
      }


      static public function get_raw_export($select) {
        $result =  AccStock::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','asc')->get(['row_number',DB::raw($select)]);        
        return $result;
      } 
}
