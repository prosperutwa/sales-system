<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use App\Models\Systems\BiovetTechUser;
use App\Models\Auth\BiovetTechAuth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $userId = Session::get('biovet_user_id');

            if ($userId) {
                $user = BiovetTechUser::find($userId);
                $auth = BiovetTechAuth::where('user_id', $userId)->first();

                $view->with('currentUser', $user)
                     ->with('currentAuth', $auth);
            } else {
                $view->with('currentUser', null)
                     ->with('currentAuth', null);
            }
        });
    }
}
