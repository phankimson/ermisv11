<?php

namespace App\Http\Model\Imports;

use App\Http\Model\User;
use Illuminate\Support\Facades\Hash;
use App\Classes\Convert;
use App\Http\Model\Country;
use App\Http\Model\Company;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;

class UserImport implements ToModel, WithHeadingRow, WithBatchInserts , WithLimit
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
        $country = Country::WhereDefault('code',$row['country'])->first();
        $company = Company::WhereDefault('code',$row['company_default'])->first();
        $user_check = User::WhereCheck('username',$row['username'],'id',null)->first();
        //dump($row);
      if($user_check == null && $row['username']){
        $arr = [
          'id'     => Str::uuid()->toString(),
          'username'    => $row['username'],
          'password'    => Hash::make($row['password']),
          'fullname'    => $row['fullname'],
          'firstname'    => $row['firstname'],
          'lastname'    => $row['lastname'],
          'identity_card'    => $row['identity_card'],
          'birthday'    => Convert::dateDefaultformat($row['birthday'],'Y-m-d'),
          'phone'    => $row['phone'],
          'email'    => Convert::StringDefaultformat($row['email']),
          'address'    => $row['address'],
          'city'    => $row['city'],
          'jobs'    => $row['jobs'],
          'country'    => $country == null ? 0 : $country->id,
          'about'    => $row['about'],
          'role'    => Convert::intDefaultNumberformat($row['role'],1),
          'avatar'    => 'addon/img/avatar.png',
          'active_code' => Str::random(30),
          'barcode'    => $row['barcode'],
          'company_default'    => $company == null ? 0 : $company->id,
          'active'    => $row['active'] == null ? 1 : $row['active'],
        ];
          $data = new UserImport();
          $data->setData($arr);
        return new User($arr);
      }
    }
   public function batchSize(): int
    {
      return (int) config('excel.setting.IMPORT_SIZE');
    }   
  
     public function limit(): int
     {
      return (int) config('excel.setting.IMPORT_LIMIT');
     }
     
     public function headingRow(): int
     {
         return (int) config('excel.setting.HEADING_ROW');
     }

}
