<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Model\PosProduct;
use App\Http\Model\PosWarehouse;

class PosHomeController extends Controller
{
    public function show()
    {
        // Lay danh sach san pham dang hoat dong de load len man hinh POS.
        $products = PosProduct::get_active_list()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => (float) $product->sale_price,
                ];
            })
            ->values();

        // Lay danh sach kho dang hoat dong de nguoi dung chon khi lap chung tu.
        $warehouses = PosWarehouse::get_active_list()
            ->map(function ($warehouse) {
                return [
                    'id' => $warehouse->id,
                    'name' => $warehouse->name,
                    'code' => $warehouse->code,
                ];
            })
            ->values();

        // Truyen du lieu boot cho giao dien POS.
        return view('pos.index', [
            'posBoot' => [
                'products' => $products,
                'warehouses' => $warehouses,
                'saleEndpoint' => route('pos.sales.store'),
                'returnEndpoint' => route('pos.returns.store'),
                'stockInEndpoint' => route('pos.stock-in.store'),
                'stockOutEndpoint' => route('pos.stock-out.store'),
                'stockTransferEndpoint' => route('pos.stock-transfer.store'),
                'csrfToken' => csrf_token(),
                'today' => now()->toDateString(),
            ],
        ]);
    }
}
