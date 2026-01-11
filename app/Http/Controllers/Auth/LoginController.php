<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\Auth\BiovetTechAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Systems\BiovetTechUser;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function loginPage(){

     

        if (Session::has('biovet_user_id')) {

            return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Welcome again, ' . Session::get('biovet_user_name'));

        } else {
            return view('templates.auth.login');
        }
        
    }

    public function loginAuth(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $auth = BiovetTechAuth::where('username', $request->username)->first();

        if (!$auth) {
            Session::flash('error', 'Incorrect credentials');
            return redirect()->back();
        }

        $user = BiovetTechUser::find($auth->user_id);

        if (!$user || $user->status != 'active') {
            Session::flash('error', 'Account disabled');
            return redirect()->back();
        }

        if (!Hash::check($request->password, $auth->password)) {
            Session::flash('error', 'Incorrect credentials');
            return redirect()->back();
        }

        Session::put('biovet_user_id', $user->auto_id);
        Session::put('biovet_user_name', $user->first_name . ' ' . $user->last_name);
        Session::put('biovet_user_role', $user->role);

        return redirect()->route('admin.dashboard')->with('success', 'Welcome, '.$user->first_name. ' '.$user->last_name);
    }

    public function logout()
    {
        session()->flush(); 
        return redirect()->route('login.page')->with('success', 'Logged out successfully.');
    }

}
