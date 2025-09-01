<?php

namespace App\Http\Model\Imports;

use App\Http\Model\Company;
use App\Http\Model\Regions;
use App\Http\Model\Country;
use App\Http\Model\Area;
use App\Http\Model\Distric;
use Illuminate\Support\Str;
use App\Classes\Convert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;

class CompanyImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit
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
        $area = Area::WhereDefault('code',$row['area'])->first();
        $distric = Distric::WhereDefault('code',$row['distric'])->first();
        $country = Country::WhereDefault('code',$row['country'])->first();
        $regions = Regions::WhereDefault('code',$row['regions'])->first();
        $code_check = Company::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null && $row['code']){
        //dump($row);
        $arr = [
          'id'     => Str::uuid()->toString(),
          'code'    => $row['code'],
          'name'    => $row['name'],
          'address'    => $row['address'],
          'email'    => $row['email'],
          'tax_code'    => Convert::StringDefaultformat($row['tax_code']),
          'director'    => $row['director'],
          'phone'    => $row['phone'],
          'fax'    => $row['fax'],
          'full_name_contact'    => $row['full_name_contact'],
          'address_contact'    => $row['address_contact'],
          'title_contact'    => $row['title_contact'],
          'email_contact'    => $row['email_contact'],
          'telephone1_contact'    => $row['telephone1_contact'],
          'telephone2_contact'    => $row['telephone2_contact'],
          'country'    =>  $country == null ? 0 : $country->id,
          'regions'    => $regions == null ? 0 : $regions->id,
          'area'    =>  $area == null ? 0 : $area->id,
          'distric'    => $distric == null ? 0 : $distric->id,
          'marketing'    => $row['marketing'],
          'company_size'    => $row['company_size'],
          'level'    => Convert::intDefaultformat($row['level']),
          'active'    => $row['active'] == null ? 1 : $row['active'],
        ];
        $data = new CompanyImport();
        $data->setData($arr);
        return new Company($arr);
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
