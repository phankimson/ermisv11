<?php

namespace App\Http\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

use Illuminate\Database\Eloquent\Model;

class AccAccountSystems extends Model
{
    use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';

      protected $table = 'account_systems';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on
      protected $keyType = 'string';

      protected static function booted()
      {
          static::BootedBaseTrait();
      }


      static public function get_code($code) {
        $result = AccAccountSystems::where('code',$code)->first();
        return $result;
      }

      static public function get_code_like($document,$code) {
        $result = AccAccountSystems::where('document_id',$document)->where('code','like',$code)->where('active',1)->get();
        return $result;
      }

      static public function get_code_wherein($document,$arr_code) {
        $result = AccAccountSystems::where('document_id',$document)->whereIn('code',$arr_code)->where('active',1)->get();
        return $result;
      }

      static public function get_wherein_id($document,$arr) {
        $result = AccAccountSystems::where('document_id',$document)->whereIn('id',$arr)->where('active',1)->orderBy('code','asc')->get();
        return $result;
      }

      static public function get_code_not_id($code,$id) {
        $result = AccAccountSystems::where('code',$code)->where('id','!=',$id)->get();
        return $result;
      }

      static public function get_child($parent) {
        $result = AccExcise::where('parent_id',$parent)->get();
        return $result;
      }


      static public function get_raw() {
        $result = AccAccountSystems::WithRowNumberDb('mysql2')->orderBy('row_number','desc')->get();
        //$result = DB::select(DB::raw("SELECT t.* from (SELECT @i:=@i+1 as row_number, s.* FROM country s, (SELECT @i:=0) AS temp order by s.created_at asc) t order by t.row_number desc"));
        return $result;
      }

      static public function get_raw_export($select) {
        $env = env("DB_DATABASE");
        $result = AccAccountSystems::WithRowNumberDb('mysql2')->orderBy('row_number','asc')
        ->leftJoin('account_type as a', 't.type', '=', 'a.id')
        ->leftJoin('account_nature as n', 't.nature', '=', 'n.id')
        ->leftJoin('account_systems as p', 't.parent_id', '=', 'p.id')
        ->leftJoin($env.'.document as d', 't.document_id', '=', 'd.id')
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }   

      public function account()
    {
        return $this->hasMany(AccAccountSystems::class,'parent_id','id');
    }

    public function parent(){
      return $this->belongsTo(self::class,'parent_id');
    }
    public function children(){
      return $this->hasMany(self::class,'parent_id')->where('active',1);
    }
    public function grandChildren(){
      return $this->children()->with('grandChildren');
    }
}
