<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;

class Permission extends Model
{
  use ScopesTraits,BootedTraits;
      protected $table = 'permission';
      public $incrementing = false;
      protected $casts = [
        'id' => 'string'
    ];

        protected static function booted()
    {
        static::BootedBaseTrait();
    }


      static public function get_user_permission($link,$user_id) {
        $result = Permission::where('user_id',$user_id)->join('menu', 'menu.id', 'permission.menu_id')->where('menu.link',$link)->first();
        return $result;
      }

      static public function get_user_permission_all($user_id) {
        $result = Permission::where('user_id',$user_id)->get();
        return $result;
      }
}
