<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use Illuminate\Support\Facades\DB;

class AccSuppliesGoodsType extends Model
{
      use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';
      protected $table = 'supplies_goods_type';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected static function booted()
      {
          static::BootedBaseTrait();
      }

      static public function get_filter($filter) {
        $result = AccSuppliesGoodsType::where('filter',$filter)->first();       
        return $result;
      }
   
      static public function get_raw() {
        $result = AccSuppliesGoodsType::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','desc')->get();       
        return $result;
      }

      static public function get_raw_skip_page($skip,$limit,$orderBy,$asc) {
        $result = AccSuppliesGoodsType::WithRowNumberDb(env('CONNECTION_DB_ACC'),$orderBy,$asc)->orderBy('row_number','desc')->skip($skip)->take($limit)->get();  
        return $result;
      }

      static public function get_raw_skip_filter_page($skip,$limit,$orderBy,$asc,$filter) {
        $result = AccSuppliesGoodsType::WithRowNumberWhereRawColumnDb(env('CONNECTION_DB_ACC'),$filter,$orderBy,$asc)->skip($skip)->take($limit)->get();  
        return $result;
      }

      static public function get_raw_export($select,$skip,$limit) {
        $result =  AccSuppliesGoodsType::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','asc')->skip($skip)->take($limit)
        ->leftJoin('account_systems as a', 'a.id', '=', 't.account_default')
        ->get(['row_number',DB::raw($select)]);       
        return $result;
      } 
}
