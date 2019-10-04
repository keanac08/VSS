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
        $host = $_SERVER['HTTP_HOST'];
        Auth::logout();
        // Session::flush();
        // return redirect()->intended('http://'.$host.'/webapps/dashboard');
        return redirect()->intended('http://ecommerce4/webapps/login/logout');
    }
}
