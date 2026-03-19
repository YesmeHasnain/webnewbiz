<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home()
    {
        return view('site.home');
    }

    public function about()
    {
        return view('site.about');
    }

    public function services()
    {
        return view('site.services');
    }

    public function solutions()
    {
        return view('site.solutions');
    }

    public function pricing()
    {
        return view('site.pricing');
    }

    public function faqs()
    {
        return view('site.faqs');
    }

    public function contact()
    {
        return view('site.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:2000',
        ]);

        return back()->with('success', 'Thank you! We\'ll get back to you soon.');
    }
}
