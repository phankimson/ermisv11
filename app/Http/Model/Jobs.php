<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use DB;

class Jobs extends Model
{
      use ScopesTraits;
      protected $table = 'jobs';
      public $incrementing = true; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on
      public $timestamps = FALSE;
      protected $casts = [
          'created_at' => 'int',
          'available_at' => 'int',
          'created_at' => 'int',         
      ];
      
      static public function get_raw() {
        $result = Jobs::WithRowNumber()->orderBy('row_number','desc')->get();
        return $result;
      }

      static public function get_raw_skip_page($skip,$limit,$orderBy,$asc) {
        $result = Jobs::WithRowNumber($orderBy,$asc)->skip($skip)->take($limit)->get();  
        return $result;
      }
    
      static public function get_raw_skip_filter_page($skip,$limit,$orderBy,$asc,$filter) {
        $result = Jobs::WithRowNumberWhereRawColumn($filter,$orderBy,$asc)->skip($skip)->take($limit)->get();  
        return $result;
      }
     

      static public function get_raw_export($select) {
        $result = Jobs::WithRowNumber()->orderBy('row_number','asc')->get(['row_number',DB::raw($select)]);
        return $result;
      }

}
