<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CitizenController extends Controller
{
    /**
     * Display the citizen portal home page.
     */
    public function index(): View
    {
        return view('citizen.index');
    }
}
