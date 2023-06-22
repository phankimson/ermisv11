<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\User;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;

class Timeline extends Model
{
  use ScopesTraits,BootedTraits;
  protected $table = 'timeline';
  protected $fillable = ['message', 'user_id'];
  public $incrementing = false; // and it doesn't even have to be auto-incrementing!

      protected static function booted()
  {
      static::BootedBaseTrait();
  }

  static public function get_timeline($skip,$limit) {
    $result = Timeline::join('users', 'users.id', 'timeline.user_id')->orderBy('timeline.created_at', 'desc')->select('timeline.*','users.username')->skip($skip)->take($limit)->get();
    return $result;
  }

  public function user() {
      return $this->belongsTo(User::class);
  }
}
