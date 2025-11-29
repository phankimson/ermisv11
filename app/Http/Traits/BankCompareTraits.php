<?php
namespace App\Http\Traits;
use App\Http\Model\AccBankCompare;

trait BankCompareTraits
{
      public function updateActiveCompare($compare_id)
    {
           $compare = AccBankCompare::find($compare_id);
              if($compare){
                $compare->status = 1;
                $compare->save();
              }
    }

    
}
