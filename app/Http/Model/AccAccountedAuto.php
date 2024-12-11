<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\AccAccountedAutoDetail;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use Illuminate\Support\Facades\DB;

class AccAccountedAuto extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';
      protected $table = 'accounted_auto';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected static function booted()
      {
          static::BootedBaseTrait();
      }


    
      static public function get_raw() {
        $result = AccAccountedAuto::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','desc')->with('accounted_auto_detail')->get();       
        return $result;
      }

      static public function get_raw_skip_page($skip,$limit,$orderBy,$asc) {
        $result = AccAccountedAuto::WithRowNumberDb(env('CONNECTION_DB_ACC'),$orderBy,$asc)->skip($skip)->take($limit)->with('accounted_auto_detail')->get();  
        return $result;
      }

      static public function get_raw_skip_filter_page($skip,$limit,$orderBy,$asc,$filter) {
        $result = AccAccountedAuto::WithRowNumberWhereRawColumnDb(env('CONNECTION_DB_ACC'),$filter,$orderBy,$asc)->skip($skip)->take($limit)->with('accounted_auto_detail')->get();  
        return $result;
      }

      static public function get_raw_export($select,$skip,$limit) {
        $result =  AccAccountedAuto::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','asc')->skip($skip)->take($limit)->get(['row_number',DB::raw($select)]);        
        return $result;
      } 

      static public function get_id_with_detail($id) {
        $result = AccAccountedAuto::where('id',$id)->with('accounted_auto_detail')->first();
        return $result;
      }

      public function accounted_auto_detail() {
        return $this->hasMany(AccAccountedAutoDetail::class,'accounted_auto','id');
      }

}
