<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;

class AccAccountSystemsFilter extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';
      protected $table = 'account_systems_filter';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on 

      protected static function booted()
      {
          static::BootedBaseTrait();
      }

      static public function get_account_systems_filter($filter,$type) {
        $result = AccAccountSystemsFilter::where('account_systems_filter_id',$filter)->where('account_systems_filter_type',$type)->get();
        return $result;
      }

      static public function get_item($filter,$type,$account_systems) {
        $result = AccAccountSystemsFilter::where('account_systems_filter_type',$type)->where('account_systems',$account_systems)->where('account_systems_filter_id',$filter)->first();
        return $result;
      }

      public function account_systems_filter()
      {
          return $this->morphTo();
      }
     
}
