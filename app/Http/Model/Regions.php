<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\Scopes\OrderByCreatedAtScope;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;



class Regions extends Model
{
    use ScopesTraits,BootedTraits;
      protected $table = 'regions';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on
      protected $attributes = [ // defaultValue
            'country' => '0',
        ];
      protected $casts = [
        'id' => 'string'
      ];

      protected static function booted()
  {
      static::BootedBaseTrait();
  }



  static public function get_raw() {
    $result = Regions::WithRowNumber()->orderBy('row_number','desc')->get();
    return $result;
  }

  static public function get_raw_export($select) {
    $result = Regions::WithRowNumber()->orderBy('row_number','asc')
    ->leftJoin('country as m', 't.country', '=', 'm.id')
    ->get(['row_number',DB::raw($select)]);
    return $result;
  }

}
