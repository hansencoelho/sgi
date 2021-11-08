<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Model\User;
use Auth;

class ControllerHome extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if (Auth::User()->primeiro_login == 1) {
            return redirect()->route('alterar_senha');
        }

        return view('dashboard.home');


    }
}
