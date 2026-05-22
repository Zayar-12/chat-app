<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Socialite;
use PhpParser\Node\Stmt\TryCatch;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:25',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        event(new Registered($user)); // email verify event(new Registered($user))

        Auth::login($user);



        return redirect()->route('home')->with('success', 'Registration successful!');
    }

    public function login(Request $request)
    {
        $credentials =    $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:4'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role == 'admin' || $user->role == 'superadmin') {
                return redirect()->route('admin.home')->with('success', 'Welcome back!');
            }
            return redirect()->route('home')->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully!');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback(Request $request)
    {
        try {

            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate([
                'email' => $googleUser->getEmail()

            ], [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
               'password' => Hash::make(str()->random(16)),
               'email_verified_at'=>now(),
            ]);

            Auth::login($user);
            
            
            $request->session()->regenerate();

            return redirect()->route('home')->with('success', 'Logged in with Google successfully!');
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['email' => 'Google Login Fail , try again']);
        }
    }
}
