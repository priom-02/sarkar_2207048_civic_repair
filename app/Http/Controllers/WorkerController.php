<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class WorkerController extends Controller
{
    /**
     * Display the worker dashboard.
     */
    public function dashboard(): View
    {
        return view('worker.dashboard');
    }
}
