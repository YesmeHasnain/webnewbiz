<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;

// Marketing Site Routes
Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/about', [SiteController::class, 'about'])->name('about');
Route::get('/services', [SiteController::class, 'services'])->name('services');
Route::get('/solutions', [SiteController::class, 'solutions'])->name('solutions');
Route::get('/pricing', [SiteController::class, 'pricing'])->name('pricing');
Route::get('/faqs', [SiteController::class, 'faqs'])->name('faqs');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::post('/contact', [SiteController::class, 'submitContact'])->name('contact.submit');
