<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

class GroupUsers extends Model
{
    use ScopesTraits,BootedTraits;
      protected $table = 'group_users';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

          protected static function booted()
      {
          static::BootedBaseTrait();
      }

      static public function get_raw() {
        $result = GroupUsers::WithRowNumber()->orderBy('row_number','desc')->get();
        return $result;
      }

      static public function get_raw_export($select) {
        $result = GroupUsers::WithRowNumber()->orderBy('row_number','asc')
        ->leftJoin('company as c', 't.company_id', '=', 'c.id')
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }

      public function scopeActiveCompany($query,$company)
      {
          return $query->where('company_id',$company)->where('active', 1);
      }
}
