<?php

namespace App\Http\Model\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use App\Http\Model\AccSystems;

class Decimal implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     */
     public function __construct()
     {
       $this->decimal = session('decimal');
       $this->decimal_symbol = session('decimal_symbol');
     }



    public function get($model, $key, $value, $attributes)
    {
        return number_format($value, $this->decimal , $this->decimal_symbol ,'');
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        return $value;
    }
}
