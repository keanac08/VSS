<?php

namespace App\Http\Controllers\Auth;

use App\Models\OracleUserModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Session;

class LoginController extends Controller
{

    public function authenticate($user_id, $source_id, OracleUserModel $OracleUserModel, UserModel $UserModel)
    {
        
        // echo 'AUTHENTICATING...';

        if($source_id == 1){
            $user = $OracleUserModel->get($user_id)[0];
            $auth = Auth::guard('oracle_user')->loginUsingId($user->user_id);
        } 
        else{
            $user = $UserModel->get($user_id)[0];
            $auth = Auth::guard('ipc_portal_user')->loginUsingId($user->user_id);
        }
        if ($auth) {
            $optional_middle = $user->middle_name ?? '';

            // User harnessed data
            $user_session = [
                'user_id'        => $user->user_id,
                'first_name'     => pascalCase($user->first_name),
                'last_name'      => pascalCase($user->last_name),
                'username'       => $user->user_name,
                'shortname'      => pascalCase($user->first_name[0] .'. '. $user->last_name),
                'fullname'       => pascalCase($user->first_name .' '. $user->last_name),
                'fullname2'      => pascalCase($user->first_name .' '. $optional_middle .' '. $user->last_name),
                'fullname3'      => pascalCase($user->last_name .' '. $user->first_name .' '. $optional_middle[0] . '.'),
                'email'          => $user->email,
                'section'        => pascalCase($user->section),
                'department'     => pascalCase($user->department),
                'division'       => pascalCase($user->division),
                'user_type_name' => $user->user_type_name,
                'customer_id'    => $user->customer_id,
                'source_id'      => $user->source_id,
                'user_type_id'   => $user->user_type_id
            ];

            // Save user instance on session
            session(['user' => $user_session]);

            // Redirect User on initial page
            return redirect()->route('dashboard');
           
        }
        else {
            return back();
        }
    }
}