<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestrictedPermission extends Controller
{

  public function index()
  {
    return view('restricted_permission');
  }
}
