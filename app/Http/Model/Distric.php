<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\Scopes\OrderByCreatedAtScope;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

class Distric extends Model
{
     use ScopesTraits,BootedTraits;
      protected $table = 'distric';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected static function booted()
  {
      static::BootedBaseTrait();
  }

  static public function get_raw() {
    $result = Distric::WithRowNumber()->orderBy('row_number','desc')->get();
    //$result = DB::select(DB::raw("SELECT t.* from (SELECT @i:=@i+1 as row_number, s.* FROM country s, (SELECT @i:=0) AS temp order by s.created_at asc) t order by t.row_number desc"));
    return $result;
  }

  static public function get_raw_export($select) {
    $result = Distric::WithRowNumber()->orderBy('row_number','asc')
    ->leftJoin('area as m', 't.area', '=', 'm.id')
    ->get(['row_number',DB::raw($select)]);
    //$result = DB::select(DB::raw("SELECT t.row_number,{$select} from (SELECT @i:=@i+1 as row_number, s.* FROM country s, (SELECT @i:=0) AS temp order by s.created_at asc) t order by t.row_number asc"));
    return $result;
  }

}
