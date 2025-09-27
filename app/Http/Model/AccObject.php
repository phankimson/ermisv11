<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class AccObject extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';

      protected $table = 'object';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on
      protected $keyType = 'string';

      protected static function booted()
      {
          static::BootedBaseTrait();
      }      
      
      static public function get_type($type){
        $result = AccObject::whereHas('object_type', function (Builder $query) use ($type) {
          $query->where('object_type', $type);
      })->where('active', 1)->get();
        return $result;
      }

       static public function get_name($name){
        $result = AccObject::where('name', $name)->orWhere('name_1',$name)->first();
        return $result;
      }

      static public function get_raw() {
        $result = AccObject::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','desc')->with('object_type')->get()->pluckDistant('object_type', 'object_type');       
        return $result;
      }
      static public function get_raw_skip_page($skip,$limit,$orderBy,$asc) {
        $result = AccObject::WithRowNumberDb(env('CONNECTION_DB_ACC'),$orderBy,$asc)->orderBy('row_number','desc')->skip($skip)->take($limit)->with('object_type')->get()->pluckDistant('object_type', 'object_type');       
        return $result;
      }
    
      static public function get_raw_skip_filter_page($skip,$limit,$orderBy,$asc,$filter) {
        $result = AccObject::WithRowNumberWhereRawColumnDb(env('CONNECTION_DB_ACC'),$filter,$orderBy,$asc)->skip($skip)->take($limit)->with('object_type')->get()->pluckDistant('object_type', 'object_type');
        return $result;
      }

      static public function get_with_balance_period($period,$type) {
        $result = AccObject::whereHas('object_type', function (Builder $query) use ($type) {
              $query->where('object_type', $type);})
        ->with(['balance' => function ($query) use ($period,$type) {
            $query->where('period', $period);
            $query->where('object_type', $type);
        }])->orderBy('code','desc')
        ->leftJoin('account_systems', 'account_systems.id', '=', 'object.account_default')->get(['object.*','account_systems.code as account_default']);
        //$result = DB::select(DB::raw("SELECT t.* from (SELECT @i:=@i+1 as row_number, s.* FROM country s, (SELECT @i:=0) AS temp order by s.created_at asc) t order by t.row_number desc"));
        return $result;
      }     

      static public function get_raw_balance_export($select,$skip,$limit,$period) {
        $result = AccObject::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','asc')->skip($skip)->take($limit)
        ->leftJoin('object_balance as s', 't.id', '=', 's.object')
        ->leftJoin('account_systems as a', 'a.id', '=', 't.account_default')
        ->where('s.period', $period)
        ->get(['row_number',DB::raw($select)]);
        //$result = DB::select(DB::raw("SELECT t.row_number,{$select} from (SELECT @i:=@i+1 as row_number, s.* FROM country s, (SELECT @i:=0) AS temp order by s.created_at asc) t order by t.row_number asc"));
        return $result;
      }

      static public function get_raw_export($select,$skip,$limit) {
        $env = env("DB_DATABASE");
        $check = 'object_type';
        if (str_contains($select, $check) == true) {
          $select = str_replace('t.'.$check.",","",$select);
          $result = AccObject::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','asc')->skip($skip)->take($limit)
          ->with([$check => function ($q) {
            $q->select('object_filter_object_type.*','object_type.id','object_type.code');
            $q->join('object_type', 'object_type.id', '=', 'object_filter_object_type.object_type');
          }])
          ->leftJoin('object_group as a', 't.object_group', '=', 'a.id')
          ->leftJoin('department as c', 't.department', '=', 'c.id')
          ->leftJoin('account_systems as b', 'b.id', '=', 't.account_default')
          ->leftJoin($env.'.regions as m', 't.regions', '=', 'm.id')
          ->leftJoin($env.'.area as n', 't.area', '=', 'n.id')
          ->leftJoin($env.'.distric as s', 't.distric', '=', 's.id')
          ->leftJoin($env.'.country as d', 't.country', '=', 'd.id')
          ->get(['row_number',DB::raw($select)]);
          $result->makeHidden('id');
          $result->pluckDistant($check, 'code');
          $result->each(function($r, $k) use ($check){
            $r->$check = $r->$check->join(",");  
            $r->unsetRelation($check);
          });    
        }else{
          $result = AccObject::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','asc')->skip($skip)->take($limit)
          ->leftJoin('object_group as a', 't.object_group', '=', 'a.id')
          ->leftJoin('department as c', 't.department', '=', 'c.id')
          ->leftJoin('account_systems as b', 'b.id', '=', 't.account_default')
          ->leftJoin($env.'.regions as m', 't.regions', '=', 'm.id')
          ->leftJoin($env.'.area as n', 't.area', '=', 'n.id')
          ->leftJoin($env.'.distric as s', 't.distric', '=', 's.id')
          ->leftJoin($env.'.country as d', 't.country', '=', 'd.id')
          ->get(['row_number',DB::raw($select)]);
        }       
        return $result;
      }

      public function object_type()
      {
          return $this->hasMany(AccObjectFilterObjectType::class, 'object', 'id');
      }

        public function balance()
    {
        return $this->hasMany(AccObjectBalance::class,'object','id');
    }
   

}
