<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;

class AccObjectFilterObjectType extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';
      protected $table = 'object_filter_object_type';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on 

      protected static function booted()
      {
          static::BootedBaseTrait();
      }

      static public function get_object($object) {
        $result = AccObjectFilterObjectType::where('object',$object)->get();
        return $result;
      }

      static public function get_item($object,$object_type) {
        $result = AccObjectFilterObjectType::where('object',$object)->where('object_type',$object_type)->first();
        return $result;
      }
     
}
