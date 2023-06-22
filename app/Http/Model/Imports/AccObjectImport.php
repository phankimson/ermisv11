<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccObject;
use App\Classes\Convert;
use App\Http\Model\AccObjectGroup;
use App\Http\Model\AccObjectType;
use App\Http\Model\Regions;
use App\Http\Model\Country;
use App\Http\Model\Area;
use App\Http\Model\Distric;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class AccObjectImport implements OnEachRow, WithHeadingRow, WithBatchInserts, WithChunkReading
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
        //dump($row);
        $code_check = AccObject::WhereCheck('code',$row['code'],'id',null)->first();
        $area = Area::WhereDefault('code',$row['area'])->first();
        $distric = Distric::WhereDefault('code',$row['distric'])->first();
        $country = Country::WhereDefault('code',$row['country'])->first();
        $regions = Regions::WhereDefault('code',$row['regions'])->first();
        $object_group = AccObjectGroup::WhereDefault('code',$row['object_group'])->first();
        if($code_check == null){
          $id = Str::uuid()->toString();
          $object = AccObject::firstOrCreate([
           'id'     => $id ,
           'object_group'    => $object_group == null ? 0 : $object_group->id,
           'code'    => $row['code'],
           'name'    => $row['name'],
           'name_1'    => $row['name_1'],
           'identity_card'    => $row['identity_card'],
           'issued_by_identity_card'    => $row['issued_by_identity_card'],
           'date_identity_card'    => Convert::dateDefaultformat($row['date_identity_card'],'Y-m-d'),
           'address'    => $row['address'],
           'email'    => $row['email'],
           'tax_code'    => $row['tax_code'],
           'invoice_form'    => $row['invoice_form'],
           'invoice_symbol'    => $row['invoice_symbol'],
           'director'    => $row['director'],
           'phone'    => $row['phone'],
           'fax'    => $row['fax'],
           'department'    => $row['department'],
           'full_name_contact'    => $row['full_name_contact'],
           'address_contact'    => $row['address_contact'],
           'title_contact'    => $row['title_contact'],
           'email_contact'    => $row['email_contact'],
           'telephone1_contact'    => $row['telephone1_contact'],
           'telephone2_contact'    => $row['telephone2_contact'],
           'bank_name'    => $row['bank_name'],
           'bank_branch'    => $row['bank_branch'],
           'bank_account'    => $row['bank_account'],
           'country'    =>  $country == null ? 0 : $country->id,
           'regions'    => $regions == null ? 0 : $regions->id,
           'area'    =>  $area == null ? 0 : $area->id,
           'distric'    => $distric == null ? 0 : $distric->id,
           'marketing'    => $row['marketing'],
           'company_size'    => $row['company_size'],
           'level'    => $row['level'],        
           'active'    => $row['active'] == null ? 1 : $row['active'],
       ]);

       $filter = explode(",",$row['object_type']);
       foreach ($filter as $f){
           $object_type = AccObjectType::WhereDefault('code',$f)->first();
           if($object_type){
            $object->object_type()->create([
                'id' => Str::uuid()->toString(),
                'object' => $id,
                'object_type' => $object_type->id,
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
