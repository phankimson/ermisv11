<?php

namespace App\Http\Model;

use App\Http\Traits\BootedTraits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public static function get_daily_summary(string $date)
    {
        return self::query()
            ->select('type', DB::raw('COUNT(*) as total_docs'), DB::raw('SUM(total_amount) as total_amount'))
            ->whereDate('transaction_date', $date)
            ->groupBy('type')
            ->orderBy('type')
            ->get();
    }

    public static function get_recent_by_date(string $date, int $limit = 20)
    {
        return self::query()
            ->with(['warehouse:id,name', 'warehouseTo:id,name'])
            ->whereDate('transaction_date', $date)
            ->latest('created_at')
            ->limit($limit)
            ->get(['id', 'code', 'type', 'transaction_date', 'warehouse_id', 'warehouse_to_id', 'total_amount']);
    }
}
