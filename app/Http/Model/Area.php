<?php

namespace App\Http\Model;
use App\Http\Model\Scopes\OrderByCreatedAtScope;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
      use ScopesTraits,BootedTraits;
      protected $table = 'area';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on
      protected $hidden = array('created_at','updated_at');

      protected static function booted()
  {
      static::BootedBaseTrait();
  }

  static public function get_raw() {
    $result = Area::WithRowNumber()->orderBy('row_number','desc')->get();
    //$result = DB::select(DB::raw("SELECT t.* from (SELECT @i:=@i+1 as row_number, s.* FROM country s, (SELECT @i:=0) AS temp order by s.created_at asc) t order by t.row_number desc"));
    return $result;
  }

  static public function get_raw_skip_page($skip,$limit,$orderBy,$asc) {
    $result = Area::WithRowNumberDb('mysql2',$orderBy,$asc)->skip($skip)->take($limit)->get();  
    return $result;
  }

  static public function get_raw_skip_filter_page($skip,$limit,$orderBy,$asc,$filter) {
    $result = Area::WithRowNumberWhereRawColumnDb('mysql2',$filter,$orderBy,$asc)->skip($skip)->take($limit)->get();  
    return $result;
  }


  static public function get_raw_export($select) {
    $result = Area::WithRowNumber()->orderBy('row_number','asc')
    ->leftJoin('regions as m', 't.regions', '=', 'm.id')
    ->get(['row_number',DB::raw($select)]);
    return $result;
  }

}
