<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;

class Chat extends Model
{
  use ScopesTraits,BootedTraits;

  protected $table = 'chat';
  public $incrementing = false; // and it doesn't even have to be auto-incrementing!
  protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected static function booted()
  {
      static::BootedBaseTrait();
  }
    //
    static public function get_chat($user_send,$user_receipt,$skip,$limit) {
      $result = Chat::where([['user_send', '=', $user_send],['user_receipt','=',$user_receipt]])->orWhere([['user_send', '=', $user_receipt],['user_receipt','=',$user_send]])->orderBy('created_at', 'desc')->skip($skip)->take($limit)->get();
      return $result;
    }

}
