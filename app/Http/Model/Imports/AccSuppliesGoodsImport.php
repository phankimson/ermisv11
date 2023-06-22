<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccExcise;
use App\Http\Model\AccStock;
use App\Http\Model\AccSuppliesGoods;
use App\Http\Model\AccUnit;
use App\Http\Model\AccSuppliesGoodsType;
use App\Http\Model\AccSuppliesGoodsGroup;
use App\Http\Model\AccVat;
use App\Http\Model\AccWarrantyPeriod;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class AccSuppliesGoodsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
  public function sheets(): array
    {
        return [
            new FirstSheetImport()
        ];
    }

  /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null    */

    public function model(array $row)
    {
        //dump($row);
        $unit = AccUnit::WhereDefault('code',$row['unit'])->first();
        $type = AccSuppliesGoodsType::WhereDefault('code',$row['type'])->first();
        $group = AccSuppliesGoodsGroup::WhereDefault('code',$row['group'])->first();
        $stock = AccStock::WhereDefault('code',$row['stock_default'])->first();
        $warranty_period = AccWarrantyPeriod::WhereDefault('code',$row['warranty_period'])->first();
        $stock_account = AccAccountSystems::WhereDefault('code',$row['stock_account'])->first();
        $revenue_account = AccAccountSystems::WhereDefault('code',$row['revenue_account'])->first();
        $cost_account = AccAccountSystems::WhereDefault('code',$row['cost_account'])->first();
        $vat_tax = AccVat::WhereDefault('code',$row['vat_tax'])->first();
        $excise_tax = AccExcise::WhereDefault('code',$row['excise_tax'])->first();
        $code_check = AccSuppliesGoods::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null){
        return new AccSuppliesGoods([
           'id'     => Str::uuid()->toString(),
           'code'    => $row['code'],
           'name'    => $row['name'],
           'name_en'    => $row['name_en'],
           'description'    => $row['description'],
           'unit_id'    => $unit == null ? 0 : $unit->id,
           'type'    => $type == null ? 0 : $type->id,
           'group'    => $group == null ? 0 : $group->id,
           'interpretations_buy'    => $row['interpretations_buy'],
           'interpretations_sell'    => $row['interpretations_sell'],
           'warranty_period'    => $warranty_period == null ? 0 : $warranty_period->id,
           'minimum_stock_quantity'    => $row['minimum_stock_quantity'],
           'maximum_stock_quantity'    => $row['maximum_stock_quantity'],
           'origin'    => $row['origin'],
           'stock_default'    =>  $stock == null ? 0 : $stock->id,
           'stock_account'    => $stock_account == null ? 0 : $stock_account->id,
           'revenue_account'    => $revenue_account == null ? 0 : $revenue_account->id,
           'cost_account'    => $cost_account == null ? 0 : $cost_account->id,
           'percent_purchase_discount'    => $row['percent_purchase_discount'],
           'purchase_discount'    => $row['purchase_discount'],
           'price_purchase'    => $row['price_purchase'],
           'price'    => $row['price'],
           'vat_tax'    => $vat_tax == null ? 0 : $vat_tax->id,
           'import_tax'    => $row['import_tax'],
           'export_tax'    => $row['export_tax'],
           'excise_tax'    => $excise_tax == null ? 0 : $excise_tax->id,
           'identity'    => $row['identity'],
           'active'    => $row['active'] == null ? 1 : $row['active'],
       ]);
      }
    }

    public function batchSize(): int
   {
       return 1000;
   }

    public function chunkSize(): int
   {
       return 1000;
   }

}
