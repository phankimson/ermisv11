<?php

namespace App\Http\Model;
use App\Http\Model\Scopes\OrderByCreatedAtScope;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

use Illuminate\Database\Eloquent\Model;

class KeyAi extends Model
{
      use ScopesTraits,BootedTraits;
      protected $table = 'key_ai';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected static function booted()
  {
      static::BootedBaseTrait();
  }

  static public function get_raw() {
    $result = KeyAi::WithRowNumber()->orderBy('row_number','desc')->get();
    //$result = DB::select(DB::raw("SELECT t.* from (SELECT @i:=@i+1 as row_number, s.* FROM country s, (SELECT @i:=0) AS temp order by s.created_at asc) t order by t.row_number desc"));
    return $result;
  }

  static public function get_raw_export($select) {
    $result =  KeyAi::WithRowNumber()->orderBy('row_number','asc')->get(['row_number',DB::raw($select)]);
    //$result = DB::select(DB::raw("SELECT t.row_number,{$select} from (SELECT @i:=@i+1 as row_number, s.* FROM country s, (SELECT @i:=0) AS temp order by s.created_at asc) t order by t.row_number asc"));
    return $result;
  }


  static public function get_name($name,$lang){
      if($lang == "vi"){
        $result = KeyAi::where("name",$name)->first();
      }else{
        $result = KeyAi::where("name_en",$name)->first();
      }
      return $result;
  }

}
