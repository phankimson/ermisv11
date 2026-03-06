<?php

namespace App\Http\Model;

use App\Http\Traits\BootedTraits;
use Illuminate\Database\Eloquent\Model;

class PosTransactionItem extends Model
{
    use BootedTraits;

    protected $connection = 'mysql3';
    protected $table = 'transaction_items';
    public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::BootedBaseTrait();
    }

    public function transaction()
    {
        return $this->belongsTo(PosTransaction::class, 'transaction_id');
    }

    public function product()
    {
        return $this->belongsTo(PosProduct::class, 'product_id');
    }

    public static function get_by_transaction(string $transactionId)
    {
        return self::query()
            ->with('product:id,name,sku')
            ->where('transaction_id', $transactionId)
            ->orderBy('created_at')
            ->get(['id', 'transaction_id', 'product_id', 'quantity', 'unit_price', 'line_total']);
    }
}
