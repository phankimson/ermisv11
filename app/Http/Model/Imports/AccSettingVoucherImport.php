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
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\WithLimit;

class AccSettingVoucherImport implements OnEachRow, WithHeadingRow, WithBatchInserts, WithChunkReading, WithLimit
{
  private static $result = array();
  public function sheets(): array
    {
        return [
            new FirstSheetImport()
        ];
    }

    public function setData($arr)
    {
        array_push(self::$result,$arr);
    } 

    public function getData()
    {
        return self::$result;
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
        $df_text = 'AccSettingVoucherDebit';
        $cf_text = 'AccSettingVoucherCredit';
        $arr = [
            'menu_id'    => $menu == null ? 0 : $menu->id,
            'code'    => $row['code'],
            'name'    => $row['name'],
            'debit'    => $debit == null ? 0 : $debit->id,
            'vat_account'    => $vat_account == null ? 0 : $vat_account->id,
            'discount_account'    => $discount_account == null ? 0 : $discount_account->id,
            'credit'    => $credit == null ? 0 : $credit->id,    
            'active'    => $row['active'] == null ? 1 : $row['active'],
        ];
        $data = new AccSettingVoucherImport();
        $group = AccSettingVoucher::firstOrCreate($arr);
         // refresh khi tạo xong
         if ($group->wasRecentlyCreated) {
            $group->refresh();
        };
        // Lấy lại id đã tạo
        $arr['id'] = $group->id;
        // Tách tài khoản debit filter
        if($row['debit_filter']){
            $filter = explode(",",$row['debit_filter']);
            $debit_filter = array();
            foreach ($filter as $f){
                $account_systems = AccAccountSystems::WhereDefault('code',$f)->first();
                if($account_systems){
                    $item = [
                        'id' => Str::uuid()->toString(),
                        'account_systems_filter_id' => $arr['id'],
                        'account_systems_filter_type' => $df_text,
                        'account_systems' => $account_systems->id,
                    ];
                 array_push($debit_filter,$item);
                $group->debit_filter()->create($item);
                }           
            }
             // Lấy pluck tài khoản debit
             $a = Arr::pluck($debit_filter, 'account_systems');
             $arr['debit_filter'] = $a;    
        }
        // Tách tài khoản credit filter
        if($row['credit_filter']){
            $filter = explode(",",$row['credit_filter']);
            $credit_filter = array();
            foreach ($filter as $f){
                $account_systems = AccAccountSystems::WhereDefault('code',$f)->first();
                if($account_systems){
                    $item = [
                        'id' => Str::uuid()->toString(),
                        'account_systems_filter_id' =>$arr['id'],
                        'account_systems_filter_type' => $cf_text,
                        'account_systems' => $account_systems->id,
                    ];
                    array_push($credit_filter,$item);
                $group->credit_filter()->create($item);
                }           
            }
             // Lấy pluck tài khoản credit
             $a = Arr::pluck($credit_filter, 'account_systems');
             $arr['credit_filter'] = $a;    
        }
        $data->setData($arr);
    }
 }

 public function batchSize(): int
 {
     return 200;
 }

  public function chunkSize(): int
 {
     return 200;
 }

 public function limit(): int
 {
     return 1000;
 }

}
