<?php

namespace App\Http\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

class AccNaturalResources extends Model
{
      use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';
      protected $table = 'natural_resources';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected static function booted()
  {
      static::BootedBaseTrait();
  }

      static public function get_code($code) {
        $result = AccNaturalResources::where('code',$code)->first();
        return $result;
      }

      static public function get_code_not_id($code,$id) {
        $result = AccNaturalResources::where('code',$code)->where('id','!=',$id)->get();
        return $result;
      }

      static public function get_child($parent) {
        $result = AccExcise::where('parent_id',$parent)->get();
        return $result;
      }

      static public function get_raw() {
        $result = AccNaturalResources::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','desc')->get();       
        return $result;
      }

      static public function get_raw_skip_page($skip,$limit,$orderBy,$asc) {
        $result = AccNaturalResources::WithRowNumberDb(env('CONNECTION_DB_ACC'),$orderBy,$asc)->orderBy('row_number','desc')->skip($skip)->take($limit)->get();  
        return $result;
      }

      static public function get_raw_skip_filter_page($skip,$limit,$orderBy,$asc,$filter) {
        $result = AccNaturalResources::WithRowNumberWhereRawColumnDb(env('CONNECTION_DB_ACC'),$filter,$orderBy,$asc)->skip($skip)->take($limit)->get();  
        return $result;
      }


      static public function get_raw_export($select,$skip,$limit) {
        $result = AccNaturalResources::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','asc')->skip($skip)->take($limit)
        ->leftJoin('unit as n', 't.unit_id', '=', 'n.id')
        ->leftJoin('natural_resources as p', 't.parent_id', '=', 'p.id')
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }
}
