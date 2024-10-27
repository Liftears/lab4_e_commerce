<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        // Check if the user has the 'admin' role
        if ($user->hasRole('Admin')) {
            // Redirect to the admin products index route
            return redirect()->route('products.index')->with('success', 'Logged in successfully!');
        }
    
        // Redirect to the customer home or a different route for regular users
        return redirect()->route('guest.home')->with('success', 'Logged in successfully!');
    }

    public function logout(Request $request)
{
    Auth::logout();

    // Flash a success message after logout
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    // Redirect with a success message
    return redirect()->route('guest.home')->with('success', 'You have been logged out successfully!');
}

    

}
