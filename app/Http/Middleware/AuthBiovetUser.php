<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Systems\BiovetTechUser;
use Illuminate\Support\Facades\Session;

class AuthBiovetUser
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('biovet_user_id')) {
            return redirect()->route('login.page')->with('error', 'You must be logged in to access this page.');
        }

         $user = BiovetTechUser::find(Session::get('biovet_user_id'));

        if (!$user || $user->status !== 'active') {

            Session::flush();

            return redirect()
                ->route('login.page')
                ->with('error', 'Your account is disabled');
        }

        return $next($request);
    }
}
