<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use Illuminate\Support\Facades\DB;


class Menu extends Model
{
      use ScopesTraits,BootedTraits;
      protected $connection = 'mysql';
      protected $table = 'menu';
      public $incrementing = false; // and it doesn't even have to be auto-incrementing!
      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

        protected static function booted()
      {
        static::BootedBaseTrait();
      }

      static public function get_menu_by_type($type,$parent_id) {
        $result = Menu::where('type',$type)->where('parent_id',$parent_id)->where('active',1)->with('sub_menu')->orderBy('position', 'asc')->get();
        return $result;
      }

      static public function get_menu_like_code($code) {
        $result = Menu::where('code','like',$code)->orWhere('code','=',$code)->where('active',1)->orderBy('created_at', 'asc')->get();
        return $result;
      }

      static public function get_menu_by_group($type,$group) {
        $result = Menu::where('type',$type)->where('group',$group)->orderBy('created_at', 'asc')->get();
        return $result;
      }

      static public function get_menu_by_where_in_group($type,$group) {
        $result = Menu::where('type',$type)->whereIn('group',$group)->orderBy('created_at', 'asc')->get();
        return $result;
      }

      static public function get_raw_type($type) {
        $result = Menu::WithRowNumberWhereColumn('type',$type)->orderBy('row_number','desc')->get();
        return $result;
      }

      
      static public function get_raw_skip_page($skip,$limit,$orderBy,$asc,$type) {
        $result = Menu::WithRowNumber($orderBy,$asc)->orderBy('row_number','desc')->where('type',$type)->skip($skip)->take($limit)->get();  
        return $result;
      }

      static public function get_raw_skip_filter_page($skip,$limit,$orderBy,$asc,$filter,$type) {
        $result = Menu::WithRowNumberWhereRawColumn($filter,$orderBy,$asc)->where('type',$type)->skip($skip)->take($limit)->get();  
        return $result;
      }


      static public function get_raw_export($select,$skip,$limit) {
        $result = Menu::WithRowNumber()->orderBy('row_number','asc')->skip($skip)->take($limit)
        ->leftJoin('menu as m', 't.parent_id', '=', 'm.id')
        ->leftJoin('software as c', 't.type', '=', 'c.id')
        ->get(['row_number',DB::raw($select)]);
        return $result;
      }

      static public function get_menu_droplist($type,$name) {
        $result = Menu::select('id as value', DB::raw("CONCAT(code ,' - ', {$name}) as text"))->where('type',$type)->get();
        return $result;
      }

      static public function get_menu_by_url($type,$link) {
        $result = Menu::where('menu.type',$type)->join('software', 'software.id', '=', 'menu.type')->where('menu.link',$link)->select('software.name as sw_name','software.name_en as sw_name_en','menu.*')->first();
        return $result;
      }

      public function sub_menu(){
      return $this->hasMany(Menu::class, 'parent_id')->with('sub_menu1')->where('active',1);
    }
      public function sub_menu1(){
      return $this->hasMany(Menu::class, 'parent_id')->where('active',1);
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
