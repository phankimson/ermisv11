<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\User;

class Message extends Model
{
  protected $fillable = ['message', 'user_id'];

  public function user() {
      return $this->belongsTo(User::class);
  }
}
