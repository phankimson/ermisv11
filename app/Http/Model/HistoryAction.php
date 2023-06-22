<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\Menu;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

class HistoryAction extends Model
{
    use ScopesTraits,BootedTraits;
    protected $table = 'history_action';
    public $incrementing = false; // and it doesn't even have to be auto-incrementing!
    protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on
    //

    protected static function booted()
{
    static::BootedBaseTrait();
}

    static public function get_raw_type($type) {
      $result = HistoryAction::WithRowNumberWhereColumn('type',$type)->orderBy('row_number','desc')->get();
      return $result;
    }

    static public function get_raw_export($select) {
      $result = HistoryAction::WithRowNumber()->orderBy('row_number','asc')
      ->leftJoin('menu as m', 't.menu', '=', 'm.id')
      ->leftJoin('users as u', 't.user', '=', 'u.id')
      ->get(['row_number',DB::raw($select)]);
      return $result;
    }

    static public function get_skip($user,$skip,$limit) {
      $result = HistoryAction::where('user',$user)->orderBy('created_at', 'desc')->with('menus')->skip($skip)->take($limit)->get();
      return $result;
    }
    static public function get_menu($user,$limit,$array_type) {
      $result = HistoryAction::where('user',$user)->orderBy('created_at', 'desc')->whereIn('type', $array_type)->with('menus')->limit($limit);
      return $result;
    }
    static public function get_type_all() {
      $result = HistoryAction::WithRowNumber()->get();
      return $result;
    }

    static public function get_count($user) {
      $result = HistoryAction::where('user',$user)->count();
      return $result;
    }


    public function menus()
    {
        return $this->hasOne(Menu::class, 'id', 'menu');
    }
}
