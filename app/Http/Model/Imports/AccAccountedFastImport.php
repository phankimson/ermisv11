<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccAccountedFast;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccBankAccount;
use App\Http\Model\AccCaseCode;
use App\Http\Model\AccCostCode;
use App\Http\Model\AccDepartment;
use App\Http\Model\AccObject;
use App\Http\Model\AccStatisticalCode;
use App\Http\Model\AccWorkCode;

class AccAccountedFastImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
      $debit = AccAccountSystems::WhereDefault('code',$row['debit'])->first();
      $credit = AccAccountSystems::WhereDefault('code',$row['credit'])->first();
      $subject_debit = AccObject::WhereDefault('code',$row['subject_debit'])->first();
      $subject_credit = AccObject::WhereDefault('code',$row['subject_credit'])->first();
      $case_code = AccCaseCode::WhereDefault('code',$row['case_code'])->first();
      $cost_code = AccCostCode::WhereDefault('code',$row['cost_code'])->first();
      $statistical_code = AccStatisticalCode::WhereDefault('code',$row['statistical_code'])->first();
      $work_code = AccWorkCode::WhereDefault('code',$row['work_code'])->first();
      $department = AccDepartment::WhereDefault('code',$row['department'])->first();
      $bank_account = AccBankAccount::WhereDefault('bank_account',$row['bank_account'])->first();
      $code_check = AccAccountedFast::WhereCheck('code',$row['code'],'id',null)->first();
      if($code_check == null){
        return new AccAccountedFast([
         'id'     => Str::uuid()->toString(),
         'code'    => $row['code'],
         'name'    => $row['name'],
         'debit'    => $debit == null ? 0 : $debit->id,
         'credit'    => $credit == null ? 0 : $credit->id,
         'subject_debit'    => $subject_debit == null ? 0 : $subject_debit->id,
         'subject_credit'    => $subject_credit == null ? 0 : $subject_credit->id,
         'case_code'    => $case_code == null ? 0 : $case_code->id,
         'cost_code'    => $cost_code == null ? 0 : $cost_code->id,
         'statistical_code'    => $statistical_code == null ? 0 : $statistical_code->id,
         'work_code'    => $work_code == null ? 0 : $work_code->id,
         'department'    => $department == null ? 0 : $department->id,
         'bank_account'    => $bank_account == null ? 0 : $bank_account->id,        
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
