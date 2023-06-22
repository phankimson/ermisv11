<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

class AccAccountedFast extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';

      protected $table = 'accounted_fast';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected static function booted()
      {
          static::BootedBaseTrait();
      }

      
      static public function get_raw() {
        $result = AccAccountedFast::WithRowNumberDb('mysql2')->orderBy('row_number','desc')->get();       
        return $result;
      }

      static public function get_raw_export($select) {
        $result = AccAccountedFast::WithRowNumberDb('mysql2')->orderBy('row_number','asc')
        ->leftJoin('account_systems as a', 't.debit', '=', 'a.id')
        ->leftJoin('account_systems as b', 't.credit', '=', 'b.id')
        ->leftJoin('case_code as c', 't.case_code', '=', 'c.id')
        ->leftJoin('cost_code as d', 't.cost_code', '=', 'd.id')
        ->leftJoin('statistical_code as e', 't.statistical_code', '=', 'e.id')
        ->leftJoin('work_code as f', 't.work_code', '=', 'f.id')
        ->leftJoin('department as m', 't.department', '=', 'm.id')
        ->leftJoin('bank_account as n', 't.bank_account', '=', 'n.id')
        ->leftJoin('object as o', 't.subject_debit', '=', 'o.id')
        ->leftJoin('object as p', 't.subject_credit', '=', 'p.id')
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }

}
