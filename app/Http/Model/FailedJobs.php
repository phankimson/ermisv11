<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use DB;

class FailedJobs extends Model
{
      use ScopesTraits;
      protected $table = 'failed_jobs';
      public $incrementing = true; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on
      public $timestamps = FALSE;
      protected $casts = [
          'created_at' => 'int',
          'available_at' => 'int',
          'created_at' => 'int',         
      ];
      
      static public function get_check($check) {
        $result = FailedJobs::where("check",$check)->get();
        return $result;
      }

      static public function get_raw() {
        $result = FailedJobs::WithRowNumber()->orderBy('row_number','desc')->get();
        return $result;
      }

      static public function get_raw_skip_page($skip,$limit,$orderBy,$asc) {
        $result = FailedJobs::WithRowNumber($orderBy,$asc)->skip($skip)->take($limit)->get();  
        return $result;
      }
    
      static public function get_raw_skip_filter_page($skip,$limit,$orderBy,$asc,$filter) {
        $result = FailedJobs::WithRowNumberWhereRawColumn($filter,$orderBy,$asc)->skip($skip)->take($limit)->get();  
        return $result;
      }
     

      static public function get_raw_export($select) {
        $result = FailedJobs::WithRowNumber()->orderBy('row_number','asc')->get(['row_number',DB::raw($select)]);
        return $result;
      }

}
