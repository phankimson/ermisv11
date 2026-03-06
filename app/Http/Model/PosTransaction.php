<?php

namespace App\Http\Model;

use App\Http\Traits\BootedTraits;
use Illuminate\Database\Eloquent\Model;

class PosTransaction extends Model
{
    use BootedTraits;

    protected $connection = 'mysql3';
    protected $table = 'pos_ermis_transactions';
    public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::BootedBaseTrait();
    }

    public function items()
    {
        return $this->hasMany(PosTransactionItem::class, 'transaction_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(PosWarehouse::class, 'warehouse_id');
    }

    public function warehouseTo()
    {
        return $this->belongsTo(PosWarehouse::class, 'warehouse_to_id');
    }
}

