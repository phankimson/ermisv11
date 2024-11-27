<?php

namespace App\Http\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Model\Message;
use App\Http\Model\Timeline;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use Illuminate\Support\Facades\Cache;
use DB;

class AccUser extends Authenticatable
{
    use Notifiable;
    use ScopesTraits,BootedTraits;
    
    protected static function booted()
    {
        static::BootedBaseTrait();
    }
     protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [
      //  'fullname', 'email', 'password',
    //];
    public $incrementing = false; // and it doesn't even have to be auto-incrementing!
    protected $guarded = [];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','active_code',
    ];

    protected $dates = ['birthday'];

      /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeCompany($query,$company)
    {
        return $query->where('company_default',$company);
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

    static public function get_user_not_id($username,$id) {
      $result = User::where('username',$username)->where('id','!=',$id)->get();
      return $result;
    }

    static public function get_raw($company) {
      $result = User::WithRowNumberWhereColumn('company_default',$company)->get()->makeVisible(['active_code','password']);      
      return $result;
    }


    static public function get_raw_skip_page($skip,$limit,$orderBy,$asc,$company) {
      $result = User::WithRowNumber($orderBy,$asc)->where('company_default',$company)->skip($skip)->take($limit)->get()->makeVisible(['active_code','password']);
      return $result;
    }

    static public function get_raw_skip_filter_page($skip,$limit,$orderBy,$asc,$filter,$company) {
      $result = User::WithRowNumberWhereRawColumn($filter,$orderBy,$asc)->where('company_default',$company)->skip($skip)->take($limit)->get()->makeVisible(['active_code','password']);
      return $result;
    }


    static public function get_raw_export($select,$com) {
      $result = DB::select(DB::raw("SELECT @i:=@i+1 as row_number, {$select} FROM users t
        LEFT JOIN group_users m ON t.group_users_id = m.id
        LEFT JOIN country d ON t.country = d.id,
        (SELECT @i:=0) AS temp
        where company_default = {$com}
        order by row_number asc"));
      return $result;
    }


    public function messages() {
      return $this->hasMany(Message::class);
    }
    public function timeline() {
      return $this->hasMany(Timeline::class);
    }
}
