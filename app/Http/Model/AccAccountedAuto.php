<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\AccAccountedAutoDetail;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

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
        $result = AccAccountedAuto::WithRowNumberDb('mysql2')->orderBy('row_number','desc')->get();       
        return $result;
      }

      static public function get_raw_export($select) {
        $result =  AccAccountedAuto::WithRowNumberDb('mysql2')->orderBy('row_number','asc')->get(['row_number',DB::raw($select)]);        
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
