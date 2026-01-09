<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Auth\BiovetTechAuth;
use App\Http\Controllers\Controller;
use App\Models\Systems\BiovetTechUser;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function dashboard()
    {
        

        return view('templates.admin.dashboard');
    }
}
