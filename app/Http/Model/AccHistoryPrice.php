<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

class AccHistoryPrice extends Model
{
    use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';
      protected $table = 'history_price';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = [];
      
      protected static function booted()
      {
        static::BootedBaseTrait();
      }

      static public function get_raw() {
        $result = AccHistoryPrice::WithRowNumberDb('mysql2')->orderBy('row_number','desc')->get();       
        return $result;
      }

      static public function get_raw_export($select) {
        $result = AccHistoryPrice::WithRowNumberDb('mysql2')->orderBy('row_number','asc')
        ->leftJoin('supplies_goods as a', 't.supplies_goods_id', '=', 'a.id')
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }

       static public function get_product_last($supplies_goods,$type){
        $result = AccHistoryPrice::where('supplies_goods_id',$supplies_goods)->where('price_type',$type)->orderBy('updated_at', 'desc')->first();
        return $result;
      }

}
