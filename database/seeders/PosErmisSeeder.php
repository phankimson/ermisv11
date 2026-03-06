<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosErmisSeeder extends Seeder
{
    public function run(): void
    {
        $connection = env('CONNECTION_DB_POS', 'mysql3');

        $warehouseA = (string) Str::uuid();
        $warehouseB = (string) Str::uuid();
        $productA = (string) Str::uuid();
        $productB = (string) Str::uuid();
        $productC = (string) Str::uuid();

        DB::connection($connection)->table('pos_ermis_warehouses')->insert([
            [
                'id' => $warehouseA,
                'code' => 'KHO-CHINH',
                'name' => 'Kho chinh',
                'address' => 'Tru so chinh',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => $warehouseB,
                'code' => 'KHO-QUAY',
                'name' => 'Kho quay ban le',
                'address' => 'Tang 1',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::connection($connection)->table('pos_ermis_products')->insert([
            [
                'id' => $productA,
                'sku' => 'SP-0001',
                'barcode' => '893000000001',
                'name' => 'Nuoc suoi 500ml',
                'unit' => 'chai',
                'sale_price' => 8000,
                'cost_price' => 6000,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => $productB,
                'sku' => 'SP-0002',
                'barcode' => '893000000002',
                'name' => 'Banh quy bo',
                'unit' => 'goi',
                'sale_price' => 18000,
                'cost_price' => 13000,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => $productC,
                'sku' => 'SP-0003',
                'barcode' => '893000000003',
                'name' => 'Sua tuoi 1L',
                'unit' => 'hop',
                'sale_price' => 32000,
                'cost_price' => 26000,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::connection($connection)->table('pos_ermis_inventories')->insert([
            [
                'id' => (string) Str::uuid(),
                'warehouse_id' => $warehouseA,
                'product_id' => $productA,
                'quantity' => 500,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'warehouse_id' => $warehouseA,
                'product_id' => $productB,
                'quantity' => 300,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'warehouse_id' => $warehouseB,
                'product_id' => $productC,
                'quantity' => 150,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

