<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccBankReconciliation;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AccBankReconciliationDetailImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit, WithStartRow
{
  private static $result = array();
   protected $bank_account;
   protected $start_row;
   protected $row;
  function __construct($bank_account,$start_row,$row) { //this will NOT overwrite the parents construct
  $this->bank_account = $bank_account;
  $this->start_row = $start_row;
  $this->row = $row;
  }
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
       
          $arr = [
            'id'     => Str::uuid()->toString(),
            'bank_account' => $this->bank_account,
            'accounting_date'    => $row[$this->row['accounting_date']],
            'transaction_description'    => $row[$this->row['transaction_description']],
            'debit_amount'    => $row[$this->row['debit_amount']],
            'credit_amount'    => $row[$this->row['credit_amount']],
            'transaction_number'    => $row[$this->row['transaction_number']],
            'corresponsive_account'    => $row[$this->row['corresponsive_account']],
            'corresponsive_name'    => $row[$this->row['corresponsive_name']]
          ];
          $data = new AccBankReconciliationDetailImport($this->bank_account,$this->start_row, $this->row);
          $data->setData($arr);
        return new AccBankReconciliation($arr);      
    }

    public function batchSize(): int
  {
    return env("IMPORT_SIZE",100);
  }   

   public function limit(): int
   {
    return env("IMPORT_LIMIT",200);
   }

     public function startRow(): int
   {
       return $this->start_row;
   }

}
