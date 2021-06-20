<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomHomeController extends Controller
{
    //

    protected $redirect = '/login';
    //
    public function __construct()
    {
        $this->middleware("auth:member")->except('logoutUser');
    }

    public function index()
    {
        return "home";
    }

    public function logoutUser()
    {
        Auth::guard('member')->logout();
        return redirect('/');
    }
}
