<?php

namespace App\Http\Model;

use App\Http\Traits\BootedTraits;
use Illuminate\Database\Eloquent\Model;

class PosProduct extends Model
{
    use BootedTraits;

    protected $connection = 'mysql3';
    protected $table = 'pos_ermis_products';
    public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::BootedBaseTrait();
    }

    public static function get_active_list()
    {
        return self::query()
            ->where('active', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'sale_price']);
    }
}
