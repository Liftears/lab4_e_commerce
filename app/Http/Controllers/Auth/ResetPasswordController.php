<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordUpdateRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    public function showResetForm($token)
    {
        return view('auth.passwords.reset')->with(['token' => $token]);
    }

    public function reset(PasswordUpdateRequest $request)
    {
        $request->validated();

        // Find the user by email
        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages(['email' => 'No user found with this email.']);
        }

        // Update the password
        $user->password = Hash::make($request->password);
        $user->save();

        // Fire the password reset event
        event(new PasswordReset($user));

        return redirect()->route('login')->with('status', 'Password reset successfully.');
    }
}
