<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\Scopes\OrderByCreatedAtScope;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

class CompanySoftware extends Model
{
      use ScopesTraits,BootedTraits;
      protected $table = 'company_software';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on


      protected static function booted()
  {
      static::BootedBaseTrait();
  }

      static public function get_raw_type($type) {
        $result = CompanySoftware::WithRowNumberWhereColumn('software_id',$type)->orderBy('row_number','desc')->get();
        return $result;
      }

      static public function get_raw_export($select) {
        $result = CompanySoftware::WithRowNumber()->orderBy('row_number','asc')
        ->leftJoin('company as m', 't.company_id', '=', 'm.id')
        ->leftJoin('software as u', 't.software_id', '=', 'u.id')
        ->leftJoin('license as c', 't.license_id', '=', 'u.id')
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }


      static public function check_company_software($company,$software){
        $result = CompanySoftware::where('company_id',$company)->where('software_id',$software)->count();
        return $result;
      }

      static public function get_company_software($company,$software,$active) {
        $result = CompanySoftware::where('company_id',$company)->where('software_id',$software)->where('active',$active)->first();
        return $result;
      }

      static public function get_company_software_all($software) {
        $result = CompanySoftware::leftJoin('company', 'company_software.company_id', '=', 'company.id')->where('software_id',$software)->select('company_software.*','company.name')->get();
        return $result;
      }

      static public function get_company_software_get() {
        $result = CompanySoftware::leftJoin('company', 'company_software.company_id', '=', 'company.id')->where('software_id','!=',null)->select('company_software.*','company.name')->get();
        return $result;
      }

      static public function get_company_software_with_license($company,$software,$active) {
        $result = CompanySoftware::leftJoin('license', 'company_software.license_id', '=', 'license.id')->where('company_software.company_id',$company)->where('company_software.software_id',$software)->where('company_software.active',$active)->select('company_software.*','license.date_end','license.date_start')->first();
        return $result;
      }
}
