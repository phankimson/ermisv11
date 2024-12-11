<?php

namespace App\Http\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Model\Message;
use App\Http\Model\Timeline;
use App\Http\Model\Country;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable,ScopesTraits,BootedTraits;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [
      //  'fullname', 'email', 'password',
    //];

    public $incrementing = false; // and it doesn't even have to be auto-incrementing!

    //protected $guarded = ['id'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','active_code',
    ];

    protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

    protected $dates = ['birthday'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'id' => 'string'
    ];

      protected static function booted()
  {
      static::BootedBaseTrait();
  }

    static public function get_username($username,$active) {
      $result = User::where('username',$username)->where('active',$active)->first();
      return $result;
    }
    static public function get_email($email,$active) {
      $result = User::where('email',$email)->where('active',$active)->first();
      return $result;
    }
    static public function get_company($company,$active) {
      $result = User::where('company_default',$company)->where('active',$active)->get();
      return $result;
    }

    static public function get_user($username) {
      $result = User::where('username',$username)->first();
      return $result;
    }

    
  static public function get_raw() {
    $result = User::WithRowNumber()->orderBy('row_number','desc')->get()->makeVisible(['active_code','password']);
    return $result;
  }

   
  static public function get_raw_skip_page($skip,$limit,$orderBy,$asc) {
    $result = User::WithRowNumber($orderBy,$asc)->skip($skip)->take($limit)->get()->makeVisible(['active_code','password']);
    return $result;
  }

  static public function get_raw_skip_filter_page($skip,$limit,$orderBy,$asc,$filter) {
    $result = User::WithRowNumberWhereRawColumn($filter,$orderBy,$asc)->skip($skip)->take($limit)->get()->makeVisible(['active_code','password']);  
    return $result;
  }

  static public function get_raw_export($select,$skip,$limit) {
    $result = User::WithRowNumber()->orderBy('row_number','asc')->skip($skip)->take($limit)
    ->leftJoin('group_users as m', 't.group_users_id', '=', 'm.id')
    ->leftJoin('country as d', 't.country', '=', 'd.id')
    ->leftJoin('company as n', 't.company_default', '=', 'n.id')
    ->get(['row_number',DB::raw($select)]);
    return $result;
  }


    public function messages() {
      return $this->hasMany(Message::class);
    }
    public function timeline() {
      return $this->hasMany(Timeline::class);
    }
    public function country() {
      return $this->hasOne(Country::class);
    }
}
