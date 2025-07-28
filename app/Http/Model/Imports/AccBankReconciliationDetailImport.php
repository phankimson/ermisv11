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
   private $rows = 0;
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

    public function getRowCount(): int
    {
        return $this->rows; // Return the total count of imported rows
    }


  /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null    */

    public function model(array $row)
    {
        $code_check = AccBankReconciliation::WhereCheck('transaction_number',$row[$this->row['transaction_number']],'id',null)->first();
        if($code_check == null && $row[$this->row['transaction_description']] && $row[$this->row['transaction_number']]){
          ++$this->rows; // Increment the counter for each processed row
          //Convert Datetime
            // Convert the date string to a Unix timestamp
            $timestamp = strtotime($row[$this->row['accounting_date']]);
            // Format the timestamp into the desired yyyy-mm-dd format
            $new_date_time = date("Y-m-d H:i:s", $timestamp);
          $arr = [
            'id'     => Str::uuid()->toString(),
            'bank_account' => $this->bank_account,
            'accounting_date'    =>  $new_date_time,
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
