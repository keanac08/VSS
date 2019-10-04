<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Session;

class LogoutController extends Controller
{    
    public function logout()
    {
        
        Auth::logout();
        Session::flush();

        return redirect()->intended('http://'.$_SERVER['HTTP_HOST'].'/webapps/login/logout');
    }
}
