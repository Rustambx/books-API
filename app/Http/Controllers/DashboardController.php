<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showDashboard()
    {
        $this->title(__('Dashboard'));

        $this->view('home');

        return $this->render();
    }
}
