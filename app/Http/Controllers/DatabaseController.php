<?php

namespace App\Http\Controllers;

use App\Classes\SchemaDB;

class DatabaseController extends Controller
{
  public function create_database()
     {
        SchemaDB::createDB('ermis1');
     }
}
