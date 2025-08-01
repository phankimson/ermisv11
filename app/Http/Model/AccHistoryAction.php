<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\Menu;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use Illuminate\Support\Facades\DB;

class AccHistoryAction extends Model
{
    use ScopesTraits,BootedTraits;
    protected $connection = 'mysql2';
    protected $table = 'history_action';
    public $incrementing = false; // and it doesn't even have to be auto-incrementing!
    protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

    
    protected static function booted()
  {
    static::BootedBaseTrait();
  }

    static public function get_skip($user,$skip,$limit) {
      $result = AccHistoryAction::where('user',$user)->orderBy('created_at', 'desc')->with('menus')->skip($skip)->take($limit)->get();
      return $result;
    }
    static public function get_menu($user,$limit,$array_type) {
      $result = AccHistoryAction::where('user',$user)->orderBy('created_at', 'desc')->whereIn('type', $array_type)->with('menus')->take($limit)->get();
      return $result;
    }
    static public function get_type_all() {
      $result = AccHistoryAction::WithRowNumberDb(env('CONNECTION_DB_ACC'))->get();
      return $result;
    }


    static public function get_raw_skip_page($skip,$limit,$orderBy,$asc,$type) {
      $result = AccHistoryAction::WithRowNumberDb(env('CONNECTION_DB_ACC'),$orderBy,$asc)->orderBy('row_number','desc')->where('type',$type)->skip($skip)->take($limit)->get();  
      return $result;
    }

    static public function get_raw_skip_filter_page($skip,$limit,$orderBy,$asc,$filter,$type) {
      $result = AccHistoryAction::WithRowNumberWhereRawColumnDb(env('CONNECTION_DB_ACC'),$filter,$orderBy,$asc)->where('type',$type)->skip($skip)->take($limit)->get();  
      return $result;
    }

    static public function get_raw_type($type) {
      $result = AccHistoryAction::WithRowNumberWhereColumnDb(env('CONNECTION_DB_ACC'),'type',$type)->orderBy('row_number','desc')->get();
      return $result;
    }

    static public function get_raw_export($select) {
      $env = env("DB_DATABASE");
      $result = AccHistoryAction::WithRowNumberDb(env('CONNECTION_DB_ACC'))->orderBy('row_number','asc')
      ->leftJoin($env.'.menu as m', 't.menu', '=', 'm.id')
      ->leftJoin($env.'.users as u', 't.user', '=', 'u.id')
      ->get(['row_number',DB::raw($select)]);
      return $result;
    }

    static public function get_count($user) {
      $result = AccHistoryAction::where('user',$user)->count();
      return $result;
    }

    public function menus()
    {
      return $this->hasOne(Menu::class, 'id', 'menu');
    }
}
