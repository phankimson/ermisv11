<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccUser;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use App\Http\Model\Country;
use App\Classes\Convert;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class AccUserImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
        $com = session('com');
        $country = Country::WhereDefault('code',$row['country'])->first();
        $prefix_username = substr(Crypt::encryptString($com->id),0,5);
        //dump($row);
        return new AccUser([
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
           'about'    => $row['about'],
           'company_default'=> $com->id,
           'role'    => 2,
           'avatar'    => 'addon/img/avatar.png',
           'barcode'    => $row['barcode'],
           'active'    => $row['active'] == null ? 1 : $row['active'],
       ]);
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
