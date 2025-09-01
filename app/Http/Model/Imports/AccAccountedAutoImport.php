<?php

namespace App\Http\Model\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Illuminate\Support\Str;
use App\Http\Model\AccAccountedAuto;
use App\Http\Model\AccAccountedAutoDetail;
use App\Http\Model\AccAccountedFast;
use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccBankAccount;
use App\Http\Model\AccCaseCode;
use App\Http\Model\AccCostCode;
use App\Http\Model\AccDepartment;
use App\Http\Model\AccObject;
use App\Http\Model\AccStatisticalCode;
use App\Http\Model\AccWorkCode;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\WithLimit;

class AccAccountedAutoImport implements  WithHeadingRow,  WithMultipleSheets
{
  public static $first = array();
  public static $second = array();
  public function sheets(): array
    {
        return [
          new FirstSheetImport(),
          new SecondSheetImport()
        ];
    }

    public function setDataFirst($arr)
    {
        array_push(self::$first,$arr);
    } 


    public function setDataSecond($arr)
    {
        array_push(self::$second,$arr);
    } 

    public function getData()
    {
      $filtered = array();
        foreach(self::$first as $k=>$v){
          $filtered = Arr::where(self::$second, function ($value, $key) use ($v){
            return ($value['accounted_auto'] == $v['id']);          
        });
        self::$first[$k]['accounted_auto_detail'] = array_values($filtered);
        }
        return self::$first;
    }   


  /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null    */
   

}


class FirstSheetImport implements ToModel, HasReferencesToOtherSheets, WithBatchInserts,WithHeadingRow , WithLimit 
{
    public function model(array $row)
    {
      $code_check = AccAccountedAuto::WhereCheck('code',$row['code'],'id',null)->first();
      if($code_check == null && $row['code']){
        $arr = [
          'code'    => $row['code'],
          'name'    => $row['name'],
          'name_en'    => $row['name_en'],
          'description'    => $row['description'],
          'active'    => $row['active'] == null ? 1 : $row['active'],
        ];
        $data = new AccAccountedAutoImport();       
        $import = AccAccountedAuto::firstOrCreate($arr);
        // refresh khi tạo xong
        if ($import->wasRecentlyCreated) {
          $import->refresh();
      };
        $arr['id'] = $import->id;
        $data->setDataFirst($arr);
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



class SecondSheetImport implements ToModel, HasReferencesToOtherSheets, WithBatchInserts, WithHeadingRow , WithLimit
{
    public function model(array $row)
    {
      if($row['debit'] && $row['credit']){
      $accounted_auto = AccAccountedAuto::WhereDefault('code',$row['accounted_auto'])->first();
      $debit = AccAccountSystems::WhereDefault('code',$row['debit'])->first();
      $credit = AccAccountSystems::WhereDefault('code',$row['credit'])->first();
      $subject_debit = AccObject::WhereDefault('code',$row['subject_debit'])->first();
      $subject_credit = AccObject::WhereDefault('code',$row['subject_credit'])->first();
      $case_code = AccCaseCode::WhereDefault('code',$row['case_code'])->first();
      $cost_code = AccCostCode::WhereDefault('code',$row['cost_code'])->first();
      $statistical_code = AccStatisticalCode::WhereDefault('code',$row['statistical_code'])->first();
      $work_code = AccWorkCode::WhereDefault('code',$row['work_code'])->first();
      $accounted_fast = AccAccountedFast::WhereDefault('code',$row['accounted_fast'])->first();
      $department = AccDepartment::WhereDefault('code',$row['department'])->first();
      $bank_account = AccBankAccount::WhereDefault('bank_account',$row['bank_account'])->first();
      $arr = [
        'id'     => Str::uuid()->toString(),
        'accounted_auto'    => $accounted_auto == null ? 0 : $accounted_auto->id,
        'description'    => $row['description'],
        'debit'    => $debit == null ? 0 : $debit->id,
        'credit'    => $credit == null ? 0 : $credit->id,
        'subject_debit'    => $subject_debit == null ? 0 : $subject_debit->id,
        'subject_credit'    => $subject_credit == null ? 0 : $subject_credit->id,
        'case_code'    => $case_code == null ? 0 : $case_code->id,
        'cost_code'    => $cost_code == null ? 0 : $cost_code->id,
        'statistical_code'    => $statistical_code == null ? 0 : $statistical_code->id,
        'work_code'    => $work_code == null ? 0 : $work_code->id,
        'accounted_fast'    => $accounted_fast == null ? 0 : $accounted_fast->id,
        'department'    => $department == null ? 0 : $department->id,
        'bank_account'    => $bank_account == null ? 0 : $bank_account->id,         
        'active'    => $row['active'] == null ? 1 : $row['active'],
      ];
      $data = new AccAccountedAutoImport();
      $data->setDataSecond($arr);
      return new AccAccountedAutoDetail($arr);
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
