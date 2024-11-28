<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccUser;
use App\Http\Model\GroupUsers;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use App\Http\Model\Country;
use App\Classes\Convert;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;

class AccUserImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit
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

    public function model(array $row)
    {
        $com = session('com');
        $country = Country::WhereDefault('code',$row['country'])->first();
        $group_users = GroupUsers::WhereDefault('code',$row['group_users'])->first();
        $prefix_username = substr(Crypt::encryptString($com->id),0,5);
        //dump($row);
        $arr = [
            'id'     => Str::uuid()->toString(),
            'username'    => $prefix_username.'_'.$row['username'],
            'password'    => Hash::make($row['password']),
            'fullname'    => $row['fullname'],
            'firstname'    => $row['firstname'],
            'lastname'    => $row['lastname'],
            'identity_card'    => $row['identity_card'],
            'birthday'    => Convert::dateDefaultformat($row['birthday'],'Y-m-d'),
            'phone'    => $row['phone'],
            'email'    => $row['email'],
            'address'    => $row['address'],
            'city'    => $row['city'],
            'jobs'    => $row['jobs'],
            'country'    => $country == null ? 0 : $country->id,
            'stock_default' => null ,
            'about'    => $row['about'],
            'company_default'=> $com->id,
            'group_users_id' => $group_users == null ? 0 : $group_users->id,
            'role'    => 2,
            'avatar'    => 'addon/img/avatar.png',
            'barcode'    => $row['barcode'],
            'active'    => $row['active'] == null ? 1 : $row['active'],
        ];
        $data = new AccUserImport();
        $data->setData($arr);
        return new AccUser($arr);
    }
    public function batchSize(): int
    {
      return env("IMPORT_SIZE",100);
    }   
  
     public function limit(): int
     {
      return env("IMPORT_LIMIT",200);
     }
}
