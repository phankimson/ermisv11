<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\AccSuppliesGoodsDiscount;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use App\Http\Model\Casts\Decimal;
use DB;

class AccSuppliesGoods extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';
      protected $table = 'supplies_goods';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on
      protected $keyType = 'string';

      protected $casts = [
        'percent_purchase_discount' => Decimal::class,
        'purchase_discount' => Decimal::class,
        'price_purchase'=> Decimal::class,
        'price'=> Decimal::class,
        'active' => 'boolean',

    ];
      
      protected static function booted()
      {
          static::BootedBaseTrait();
      }

      
      static public function get_raw() {
        $result = AccSuppliesGoods::WithRowNumberDb('mysql2')->orderBy('row_number','desc')->get();      
        return $result;
      }

      static public function get_raw_export($select) {
        $result = AccSuppliesGoods::WithRowNumberDb('mysql2')->orderBy('row_number','asc')
        ->leftJoin('unit as a', 't.type', '=', 'a.id')
        ->leftJoin('supplies_goods_type as b', 't.type', '=', 'b.id')
        ->leftJoin('supplies_goods_group as c', 't.group', '=', 'c.id')
        ->leftJoin('warranty_period as d', 't.warranty_period', '=', 'd.id')
        ->leftJoin('stock as e', 't.stock_default', '=', 'e.id')
        ->leftJoin('account_systems as f', 't.stock_account', '=', 'f.id')
        ->leftJoin('account_systems as g', 't.revenue_account', '=', 'g.id')
        ->leftJoin('account_systems as h', 't.cost_account', '=', 'h.id')
        ->leftJoin('vat as j', 't.vat_tax', '=', 'j.id')
        ->leftJoin('excise as m', 't.excise_tax', '=', 'm.id')
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }   

      static public function get_id_with_discount($id) {
        $result = AccSuppliesGoods::where('id',$id)->with('discount')->first();
        return $result;
      }
   
      public function discount() {
        return $this->hasMany(AccSuppliesGoodsDiscount::class,'supplies_goods_id','id');
      }

}
