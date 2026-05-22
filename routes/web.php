<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/home', function () {
    return view('components.home');
})->middleware(['auth'])->name('home');

//auth
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login',[AuthController::class,'login'] )->name('login');


Route::get('/register', function () {
    return view('auth.register'); 
})->name('register');

Route::post('/register',[AuthController::class,'register'] )->name('register');
Route::post('/logout',[AuthController::class,'logout'])->name('logout');

Route::get('/profile' ,function(){
    return view('components.profile');
})->name('profile');




// choose the google account
Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');

// Google Callback Route
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

//email verify
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); 
    return redirect('/home'); 
})->middleware(['auth', 'signed'])->name('verification.verify');




