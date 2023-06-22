<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\Scopes\OrderByCreatedAtScope;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

class License extends Model
{
    use ScopesTraits,BootedTraits;
      protected $table = 'license';
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

      static public function get_raw_export($select) {
        $result = License::WithRowNumber()->orderBy('row_number','asc')
        ->leftJoin('company as c', 't.company_use', '=', 'c.id')
        ->leftJoin('software as s', 't.software_use', '=', 's.id')
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }

}
