<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $view = 'admin.dashboard';

    public function dashboard()
    {
        return view($this->view, get_defined_vars());
    }
}
