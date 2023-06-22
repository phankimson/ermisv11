<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccSettingVoucher;
use App\Http\Model\AccAccountSystems;
use App\Http\Model\Menu;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class AccSettingVoucherImport implements OnEachRow, WithHeadingRow, WithBatchInserts, WithChunkReading
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

    public function onRow(Row $row)
    {   
        $code_check = AccSettingVoucher::WhereCheck('code',$row['code'],'id',null)->first();
        $debit = AccAccountSystems::WhereDefault('code',$row['debit'])->first();
        $vat_account = AccAccountSystems::WhereDefault('code',$row['vat_account'])->first();
        $discount_account = AccAccountSystems::WhereDefault('code',$row['discount_account'])->first();
        $credit = AccAccountSystems::WhereDefault('code',$row['credit'])->first();
        $menu = Menu::WhereDefault('code',$row['menu'])->first();
        if($code_check == null){
        $id = Str::uuid()->toString();
        $df_text = 'AccSettingVoucherDebit';
        $cf_text = 'AccSettingVoucherCredit';
        $group = AccSettingVoucher::firstOrCreate([
           'id'     => $id,
           'menu_id'    => $menu == null ? 0 : $menu->id,
           'code'    => $row['code'],
           'name'    => $row['name'],
           'debit'    => $debit == null ? 0 : $debit->id,
           'vat_account'    => $vat_account == null ? 0 : $vat_account->id,
           'discount_account'    => $discount_account == null ? 0 : $discount_account->id,
           'credit'    => $credit == null ? 0 : $credit->id,    
           'active'    => $row['active'] == null ? 1 : $row['active'],
        ]);

            $filter = explode(",",$row['debit_filter']);
            foreach ($filter as $f){
                $account_systems = AccAccountSystems::WhereDefault('code',$f)->first();
                if($account_systems){
                $group->debit_filter()->create([
                    'id' => Str::uuid()->toString(),
                    'account_systems_filter_id' => $id,
                    'account_systems_filter_type' => $df_text,
                    'account_systems' => $account_systems->id,
                ]);
                }           
            }
            $filter = explode(",",$row['credit_filter']);
            foreach ($filter as $f){
                $account_systems = AccAccountSystems::WhereDefault('code',$f)->first();
                if($account_systems){
                $group->credit_filter()->create([
                    'id' => Str::uuid()->toString(),
                    'account_systems_filter_id' => $id,
                    'account_systems_filter_type' => $cf_text,
                    'account_systems' => $account_systems->id,
                ]);
                }           
            }
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
