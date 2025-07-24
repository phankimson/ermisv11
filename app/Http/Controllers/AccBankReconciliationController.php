<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccBankReconciliationController extends Controller
{
  protected $url;
  protected $key;
  protected $menu;
  protected $page_system;
  public function __construct(Request $request)
  {

  }

  public function show(){
    return view('acc.bank_reconciliation');
  }

}
