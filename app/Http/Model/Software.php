<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use Illuminate\Support\Facades\DB;

class Software extends Model
{
    use ScopesTraits,BootedTraits;
      protected $table = 'software';
      protected $connection = 'mysql';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on
      protected $casts = [
        'id' => 'string'
      ];

      protected static function booted()
  {
      static::BootedBaseTrait();
  }



  static public function get_url($url) {
    $result = Software::where('url',$url)->first();
    return $result;
  }

  static public function get_raw() {
    $result = Software::WithRowNumber()->orderBy('row_number','desc')->get();
    return $result;
  }

   
  static public function get_raw_skip_page($skip,$limit,$orderBy,$asc) {
    $result = Software::WithRowNumber($orderBy,$asc)->orderBy('row_number','desc')->skip($skip)->take($limit)->get();  
    return $result;
  }

  static public function get_raw_skip_filter_page($skip,$limit,$orderBy,$asc,$filter) {
    $result = Software::WithRowNumberWhereRawColumn($filter,$orderBy,$asc)->skip($skip)->take($limit)->get();  
    return $result;
  }



  static public function get_raw_export($select,$skip,$limit) {
    $result = Software::WithRowNumber()->orderBy('row_number','asc')->skip($skip)->take($limit)
    ->get(['row_number',DB::raw($select)]);
    return $result;
  }

}
