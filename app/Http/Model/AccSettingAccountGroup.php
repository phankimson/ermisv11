<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use Illuminate\Support\Facades\DB;

class AccSettingAccountGroup extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';
      protected $table = 'setting_account_group';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on
      protected $keyType = 'string';

      protected static function booted()
      {
          static::BootedBaseTrait();
      }

      static public function get_code($code) {
        $result = AccSettingAccountGroup::where('code',$code)->with('account_filter')->first();
        return $result;
      }

      static public function get_raw() {
        $result = AccSettingAccountGroup::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','desc')->with('account_filter')->get()->pluckDistant('account_filter', 'account_systems');       
        return $result;
      }

      static public function get_raw_skip_page($skip,$limit,$orderBy,$asc) {
        $result = AccSettingAccountGroup::WithRowNumberDb(env('CONNECTION_DB_ACC'),$orderBy,$asc)->with('account_filter')->orderBy('row_number','desc')->skip($skip)->take($limit)->get()->pluckDistant('account_filter', 'account_systems'); 
        return $result;
      }

      static public function get_raw_skip_filter_page($skip,$limit,$orderBy,$asc,$filter) {
        $result = AccSettingAccountGroup::WithRowNumberWhereRawColumnDb(env('CONNECTION_DB_ACC'),$filter,$orderBy,$asc)->with('account_filter')->skip($skip)->take($limit)->get()->pluckDistant('account_filter', 'account_systems');  
        return $result;
      }

      static public function get_raw_export($select,$skip,$limit) {
        $check = 'account_filter';
        if (str_contains($select, $check) == true) {
          $select = str_replace('t.'.$check.",","",$select);
          $result =  AccSettingAccountGroup::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','asc')->with([$check => function ($q) {
            $q->select('account_systems_filter.*','account_systems.id','account_systems.code');
            $q->join('account_systems', 'account_systems.id', '=', 'account_systems_filter.account_systems');
          }])->skip($skip)->take($limit)
          ->leftJoin('account_systems as a', 'a.id', '=', 't.account_default')
          ->get(['row_number','t.id',DB::raw($select)]);   
          $result->makeHidden('id');
          $result->pluckDistant($check, 'code');
          $result->each(function($r, $k) use ($check){
            $r->$check = $r->$check->join(",");  
            $r->unsetRelation($check);
          });
        }else{
          $result =  AccSettingAccountGroup::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','asc')->skip($skip)->take($limit)->get(['row_number',DB::raw($select)]);
        }
          
        return $result;
      } 


      public function account_filter()
      {
          return $this->morphMany(AccAccountSystemsFilter::class, 'account_systems_filter');
      }

         public function account()
    {
        return $this->hasOne(AccAccountSystems::class,'id','account_default');
    }


}
