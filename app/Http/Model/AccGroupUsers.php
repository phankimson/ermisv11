<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

class AccGroupUsers extends Model
{
  use ScopesTraits,BootedTraits;
      protected $table = 'group_users';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on 

      protected static function booted()
      {
          static::BootedBaseTrait();
      }
   
      static public function get_raw($company) {
        $result = AccGroupUsers::WithRowNumberWhereColumn('company_id',$company)->get();       
        return $result;
      }

      static public function get_raw_export($select,$company) {
        $result = AccGroupUsers::WithRowNumberWhereColumn('company_id',$company)
        ->leftJoin('company as c', 't.company_id', '=', 'c.id')
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }

      public function scopeActiveCompany($query,$company)
      {
          return $query->where('company_id',$company)->where('active', 1);
      }

      //static public function get_raw($company) {
      //  $result = DB::select(DB::raw("SELECT @i:=@i+1 as row_number, t.* FROM group_users t , (SELECT @i:=0) AS temp where company_id = {$company} order by row_number desc"));
      //  return $result;
      //}

      //static public function get_raw_export($select,$company) {
       // $result = DB::select(DB::raw("SELECT @i:=@i+1 as row_number, {$select} FROM group_users t
      //    LEFT JOIN company c ON t.company_id = c.id,
      //    (SELECT @i:=0) AS temp where company_id = {$company} order by row_number asc"));
       // return $result;
      //}

}
