<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;

class Notes extends Model
{
    use ScopesTraits,BootedTraits;
      protected $table = 'notes';
      public $incrementing = false;
      protected $casts = [
        'id' => 'string'
    ];
        protected static function booted()
    {
        static::BootedBaseTrait();
    }

      static public function get_notes($skip,$limit) {
        $result = Notes::orderBy('created_at', 'desc')->skip($skip)->take($limit)->get();
        return $result;
      }
}
