<?php

namespace App\Http\Model\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;

class AccBankCompareGeneralImport implements WithMappedCells,ToModel
{
  protected $row;
  public static $data = array();
  public function __construct($row)
  {
      $this->row = $row;
  }
  public function mapping(): array
  {
      return [
        'bank_account'    => $this->row['bank_account'],
        'total_credit'    => $this->row['total_credit'],
        'total_debit'    => $this->row['total_debit'],
        ];
  } 

  public function setData($arr)
  {
    array_push(self::$data,$arr);
  }   
  
  public function getData()
  {
     return self::$data[0];
  }   

  public function model(array $row)
  {            
    if($this->row){
        $arr =  [
        'bank_account'    => $row['bank_account'],
        'total_credit'    => $row['total_credit'],
        'total_debit'    => $row['total_debit'],
      ]; 
      AccBankCompareGeneralImport::setData($arr);      
      return ;
    }
    
   }

}