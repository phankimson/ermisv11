<?php

namespace App\Http\Livewire;

use App\Http\Model\PosInventory;
use App\Http\Model\PosProduct;
use App\Http\Model\PosTransaction;
use App\Http\Model\PosWarehouse;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PosDashboard extends Component
{
    public string $reportDate;
    public array $summary = [];
    public array $recent = [];
    public array $warehouses = [];
    public array $products = [];
    public array $inventories = [];

    public function mount(): void
    {
        $this->reportDate = now()->toDateString();
        $this->warehouses = PosWarehouse::query()->where('active', 1)->orderBy('name')->get(['id', 'name', 'code'])->toArray();
        $this->products = PosProduct::query()->where('active', 1)->orderBy('name')->get(['id', 'name', 'sku', 'sale_price'])->toArray();
        $this->reload();
    }

    public function updatedReportDate(): void
    {
        $this->reload();
    }

    public function reload(): void
    {
        $this->summary = PosTransaction::query()
            ->select('type', DB::raw('COUNT(*) as total_docs'), DB::raw('SUM(total_amount) as total_amount'))
            ->whereDate('transaction_date', $this->reportDate)
            ->groupBy('type')
            ->orderBy('type')
            ->get()
            ->toArray();

        $this->recent = PosTransaction::query()
            ->with(['warehouse:id,name', 'warehouseTo:id,name'])
            ->whereDate('transaction_date', $this->reportDate)
            ->latest('created_at')
            ->limit(12)
            ->get(['id', 'code', 'type', 'transaction_date', 'warehouse_id', 'warehouse_to_id', 'total_amount'])
            ->toArray();

        $this->inventories = PosInventory::query()
            ->with(['warehouse:id,name', 'product:id,name'])
            ->where('active', 1)
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get(['id', 'warehouse_id', 'product_id', 'quantity'])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.pos.dashboard');
    }
}

