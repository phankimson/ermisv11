<?php

namespace App\Http\Livewire;

use App\Http\Model\PosInventory;
use App\Http\Model\PosProduct;
use App\Http\Model\PosTransaction;
use App\Http\Model\PosWarehouse;
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
        $this->warehouses = PosWarehouse::get_active_list()->toArray();
        $this->products = PosProduct::get_active_list()->toArray();
        $this->reload();
    }

    public function updatedReportDate(): void
    {
        $this->reload();
    }

    public function reload(): void
    {
        $this->summary = PosTransaction::get_daily_summary($this->reportDate)->toArray();
        $this->recent = PosTransaction::get_recent_by_date($this->reportDate, 12)->toArray();
        $this->inventories = PosInventory::get_recent_list(20)->toArray();
    }

    public function render()
    {
        return view('livewire.pos.dashboard');
    }
}
