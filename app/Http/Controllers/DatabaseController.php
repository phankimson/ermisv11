<?php

namespace App\Http\Controllers;

use App\Classes\SchemaDB;
use Illuminate\Support\Facades\Auth;

class DatabaseController extends Controller
{
  public function create_database()
     {
        abort_unless(Auth::check(), 403);
        SchemaDB::createDB('ermis1');
     }
}
