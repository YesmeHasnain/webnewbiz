<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Website;

class HomeController extends Controller
{
    public function index()
    {
        $plans = Plan::active()->ordered()->get();
        $websiteCount = Website::where('status', 'active')->count();

        return view('home', compact('plans', 'websiteCount'));
    }

    public function pricing()
    {
        $plans = Plan::active()->ordered()->get();

        return view('pricing', compact('plans'));
    }

    public function features()
    {
        return view('features');
    }
}
