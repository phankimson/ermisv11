<?php

namespace App\Http\Traits;
use App\Http\Model\Scopes\OrderByCreatedAtScope;

trait OrderTraits {
    public static function OrderByCreatedAtBaseTrait()
    {
         // Order by name ASC
          self::addGlobalScope(new OrderByCreatedAtScope);
    }
}