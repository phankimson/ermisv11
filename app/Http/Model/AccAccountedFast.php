<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use Illuminate\Support\Facades\DB;

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
        $result = AccAccountedFast::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','desc')->get();       
        return $result;
      }
      static public function get_raw_skip_page($skip,$limit,$orderBy,$asc) {
        $result = AccAccountedFast::WithRowNumberDb(env('CONNECTION_DB_ACC'),$orderBy,$asc)->orderBy('row_number','desc')->skip($skip)->take($limit)->get();  
        return $result;
      }

      static public function get_raw_skip_filter_page($skip,$limit,$orderBy,$asc,$filter) {
        $result = AccAccountedFast::WithRowNumberWhereRawColumnDb(env('CONNECTION_DB_ACC'),$filter,$orderBy,$asc)->skip($skip)->take($limit)->get();  
        return $result;
      }

      static public function get_raw_export($select,$skip,$limit) {
         $env = env("DB_DATABASE");
        $result = AccAccountedFast::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','asc')->skip($skip)->take($limit)
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
        ->leftJoin($env.'.menu as u', 't.profession', '=', 'u.id')
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }      

      static public function get_profession($profession) {
        $result = AccAccountedFast::where(function($query) use ($profession) {
                 $query->where('profession', "0")
                       ->orWhere('profession', $profession);
             })->where('active',1)->orderBy('code','asc')->get();
        return $result;
      }

}
