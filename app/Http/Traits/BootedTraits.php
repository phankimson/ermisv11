<?php
namespace App\Http\Traits;
use Illuminate\Support\Str;

trait BootedTraits
{
      public static function BootedBaseTrait()
    {
          self::creating(function ($model) {
            $model->id = Str::uuid()->toString();
          });
    }

}
