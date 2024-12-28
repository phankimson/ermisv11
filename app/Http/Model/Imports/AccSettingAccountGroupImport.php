<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccSettingAccountGroup;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AccSettingAccountGroupImport implements OnEachRow, WithHeadingRow, WithBatchInserts, WithLimit, WithStartRow
{
  private static $result = array();
  public function sheets(): array
    {
        return [
            new FirstSheetImport(),
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
        //dump($row);
        $code_check = AccSettingAccountGroup::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null){
        $arr = 
        [
            'code'    => $row['code'],
            'name'    => $row['name'],
            'account_group'    => $row['account_group'], 
            'active'    => $row['active'] == null ? 1 : $row['active'],
        ];
        $data = new AccSettingAccountGroupImport();
        // Xài firstOrCreate tự chạy id trong BootedTraits
        $group = AccSettingAccountGroup::firstOrCreate($arr);
        // refresh khi tạo xong
        if ($group->wasRecentlyCreated) {
            $group->refresh();
        };
        // Lấy lại id đã tạo
        $arr['id'] = $group->id;
        // Tách tài khoản filter
        if($row['account_filter']){
            $filter = explode(",",$row['account_filter']);
            $account_filter = array();
            foreach ($filter as $f){
                $account_systems = AccAccountSystems::WhereDefault('code',$f)->first();
                if($account_systems){
                $item = [
                    'id' => Str::uuid()->toString(),
                    'account_systems' => $account_systems->id,
                ];            
                array_push($account_filter,$item);
                $group->account_filter()->create($item);
                }           
            }
            // Lấy pluck tài khoản
            $a = Arr::pluck($account_filter, 'account_systems');
            $arr['account_filter'] = $a;    
        }          
       $data->setData($arr);
        }
    }

    public function batchSize(): int
    {
      return env("IMPORT_SIZE",100);
    }   
  
     public function limit(): int
     {
      return env("IMPORT_LIMIT",200);
     }
     public function headingRow(): int
     {
         return env("HEADING_ROW",1);
     }
       public function startRow(): int
     {
         return env("START_ROW",2);
     }

}

