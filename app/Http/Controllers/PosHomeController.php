<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PosHomeController extends Controller
{
    public function show(){
     return view('pos.index');
    }
}
