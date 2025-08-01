<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use Illuminate\Support\Facades\DB;

class License extends Model
{
    use ScopesTraits,BootedTraits;
      protected $table = 'license';
      protected $connection = 'mysql';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

          protected static function booted()
      {
          static::BootedBaseTrait();
      }


      static public function get_license($id,$date,$active) {
        $result = License::where('id',$id)->where('date_start','<=',$date)->where('date_end','>=',$date)->where('active',$active)->first();
        return $result;
      }

      static public function get_raw() {
        $result = License::WithRowNumber()->orderBy('row_number','desc')->get();
        return $result;
      }

       
      static public function get_raw_skip_page($skip,$limit,$orderBy,$asc) {
        $result = License::WithRowNumber($orderBy,$asc)->orderBy('row_number','desc')->skip($skip)->take($limit)->get();  
        return $result;
      }

      static public function get_raw_skip_filter_page($skip,$limit,$orderBy,$asc,$filter) {
        $result = License::WithRowNumberWhereRawColumn($filter,$orderBy,$asc)->skip($skip)->take($limit)->get();  
        return $result;
      }

      static public function get_raw_export($select,$skip,$limit) {
        $result = License::WithRowNumber()->orderBy('row_number','asc')->skip($skip)->take($limit)
        ->leftJoin('company as c', 't.company_use', '=', 'c.id')
        ->leftJoin('software as s', 't.software_use', '=', 's.id')
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }

}
