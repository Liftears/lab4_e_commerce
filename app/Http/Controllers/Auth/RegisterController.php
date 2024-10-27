<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RegistersUsers;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $adminRole = Role::where('role_name', 'Admin')->first();
        $customerRole = Role::where('role_name', 'Customer')->first();

        if ($adminRole && !$adminRole->users()->exists()) {
            $user->roles()->attach($adminRole->id);
        } else {
            if ($customerRole) {
                $user->roles()->attach($customerRole->id);
            }
        }

        session()->flash('success', 'Registration successful! You are now registered.');

        return $user;
    }

    // Override the redirection after registration
    protected function redirectTo()
    {
        if (auth()->user()->hasRole('Admin')) {
            return route('products.index');
        }

        return route('guest.home');
    }
}
