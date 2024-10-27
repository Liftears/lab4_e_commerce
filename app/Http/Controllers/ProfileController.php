<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }



    
    public function updateUser(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        session()->flash('success', 'Update profile successfully!');

    return redirect()->back();
    }




    public function updatePassword(Request $request): RedirectResponse
{
    // Validate current password and new password fields
    $request->validate([
        'current_password' => ['required', 'current_password'],
        'password' => ['required', 'confirmed', 'min:8'], // Add your desired password rules
    ]);

    $user = $request->user();

    // Ensure the current password is correct
    if (!Hash::check($request->input('current_password'), $user->password)) {
        return Redirect::route('profile.edit')->withErrors(['current_password' => 'Current password is incorrect.']);
    }

    // Update the password
    $user->password = Hash::make($request->input('password'));
    $user->save();

    session()->flash('success', 'Change password successfully!');

    return redirect()->back();
}


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
{
    $user = Auth::user(); // Get the authenticated user

    // Perform any necessary checks before deletion

    // Delete the user
    $user->delete();

    // Optionally, logout the user after deletion
    Auth::logout();

    return redirect('/')->with('success', 'Account deleted successfully.');
}

}
