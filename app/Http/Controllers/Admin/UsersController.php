<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Auth\BiovetTechAuth;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;
use App\Models\Systems\BiovetTechUser;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
 
    public function index()
    {
        $users = BiovetTechUser::with('auth')->get();
        return view('templates.admin.users', compact('users'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'  => 'required|string|max:100',
            'phonenumber'=> 'required|string|max:20',
            'username'   => 'required|string|max:100|unique:biovet_tech_auth,username',
            'password'   => 'required|string|min:6',
            'role'       => 'required|string',
        ]);

        DB::transaction(function () use ($request) {

            $user = BiovetTechUser::create([
                'first_name'  => $request->first_name,
                'last_name'   => $request->last_name,
                'email'   => $request->email,
                'phonenumber' => $request->phonenumber,
                'role'        => $request->role,
                'status'      => 'active',
            ]);

            BiovetTechAuth::create([
                'user_id'  => $user->auto_id,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'status'   => 1,
            ]);
        });

        return redirect()->back()->with('success', 'User created successfully');
    }

    
    public function update(Request $request, $id)
    {
        $user = BiovetTechUser::findOrFail($id);

        $request->validate([
            'first_name' => 'required',
            'last_name'  => 'required',
            'phonenumber'=> 'required',
            'role'       => 'required',
        ]);

        $user->update($request->only([
            'first_name',
            'last_name',
            'phonenumber',
            'role'
        ]));

        return redirect()->back()->with('success', 'User updated successfully');
    }

    
    public function toggleStatus($id)
    {
        $user = BiovetTechUser::with('auth')->findOrFail($id);

        $newStatus = $user->status === 'active' ? 'inactive' : 'active';

        $newStatus1 = $user->status === 1 ? 0 : 1;

        DB::transaction(function () use ($user, $newStatus, $newStatus1) {
            $user->update(['status' => $newStatus]);
            $user->auth->update(['status' => $newStatus1]);
        });

        return redirect()->back()->with('success', 'User status updated');
    }


    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'auth_id'  => 'required|exists:biovet_tech_auth,auto_id',
            'password' => 'required|string|min:6|max:12|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }

        BiovetTechAuth::where('auto_id', $request->auth_id)
        ->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password changed successfully');
    }

    public function destroy($id)
    {
        $user = BiovetTechUser::with('auth')->findOrFail($id);

        DB::transaction(function () use ($user) {
            $user->delete();
            $user->auth->delete();
        });

        return redirect()->back()->with('success', 'User deleted');
    }
}
