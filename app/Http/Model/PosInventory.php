<?php

namespace App\Http\Model;

use App\Http\Traits\BootedTraits;
use Illuminate\Database\Eloquent\Model;

class PosInventory extends Model
{
    use BootedTraits;

    protected $connection = 'mysql3';
    protected $table = 'pos_ermis_inventories';
    public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::BootedBaseTrait();
    }

    public function warehouse()
    {
        return $this->belongsTo(PosWarehouse::class, 'warehouse_id');
    }

    public function product()
    {
        return $this->belongsTo(PosProduct::class, 'product_id');
    }

    public static function get_recent_list(int $limit = 20)
    {
        return self::query()
            ->with(['warehouse:id,name', 'product:id,name'])
            ->where('active', 1)
            ->orderByDesc('updated_at')
            ->limit($limit)
            ->get(['id', 'warehouse_id', 'product_id', 'quantity']);
    }
}
