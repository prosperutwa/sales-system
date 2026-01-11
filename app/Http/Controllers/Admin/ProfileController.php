<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Systems\BiovetTechUser;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    public function index(){

        $userId = Session::get('biovet_user_id');
        $user = BiovetTechUser::with('auth')->findOrFail($userId);
        $auth = $user->auth;


        return view('templates.admin.profile', compact('user','auth'));
        
    }

    public function updateProfile(Request $request)
    {
        $userId = $request->session()->get('biovet_user_id');
        $user = BiovetTechUser::with('auth')->findOrFail($userId);
        $auth = $user->auth;

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'phonenumber'=> 'required|string|max:20',
            'email'      => 'required|email|max:255|unique:biovet_tech_users,email,' . $user->auto_id . ',auto_id',
            'username'   => 'required|string|max:50|unique:biovet_tech_auth,username,' . $auth->auto_id . ',auto_id',
        ]);

        $user->update([
            'first_name'  => $request->first_name,
            'last_name'   => $request->last_name,
            'phonenumber' => $request->phonenumber,
            'email'       => $request->email,
        ]);

        $auth->update([
            'username' => $request->username,
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function changePassword(Request $request)
    {
        $userId = $request->session()->get('biovet_user_id');
        $user = BiovetTechUser::with('auth')->findOrFail($userId);
        $auth = $user->auth;

        $request->validate([
            'password' => 'required|string|min:8|max:12|confirmed',
        ]);

        $auth->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password changed successfully');
    }
}
