<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use Illuminate\Support\Facades\DB;

class Error extends Model
{
      use ScopesTraits,BootedTraits;
      protected $table = 'error';
      protected $connection = 'mysql';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on


          protected static function booted()
      {
          static::BootedBaseTrait();
      }

      static public function get_check($check) {
        $result = Error::where("check",$check)->get();
        return $result;
      }

      static public function get_raw() {
        $result = Error::WithRowNumber()->orderBy('row_number','desc')->get();
        //$result = DB::select(DB::raw("SELECT t.* from (SELECT @i:=@i+1 as row_number, s.* FROM country s, (SELECT @i:=0) AS temp order by s.created_at asc) t order by t.row_number desc"));
        return $result;
      }

      static public function get_raw_skip_page($skip,$limit,$orderBy,$asc,$type) {
        $result = Error::WithRowNumber($orderBy,$asc)->orderBy('row_number','desc')->where('type',$type)->skip($skip)->take($limit)->get();  
        return $result;
      }

      static public function get_raw_skip_filter_page($skip,$limit,$orderBy,$asc,$filter,$type) {
        $result = Error::WithRowNumberWhereRawColumn($filter,$orderBy,$asc)->where('type',$type)->skip($skip)->take($limit)->get();  
        return $result;
      }



      static public function get_raw_type($type) {
        $result = Error::WithRowNumberWhereColumn('type',$type)->orderBy('row_number','desc')->get();
        return $result;
      }

      static public function get_raw_export($select,$skip,$limit) {
        $result = Error::WithRowNumber()->orderBy('row_number','asc')->skip($skip)->take($limit)
        ->leftJoin('menu as m', 't.menu_id', '=', 'm.id')
        ->leftJoin('users as u', 't.user_id', '=', 'u.id')
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }

}
