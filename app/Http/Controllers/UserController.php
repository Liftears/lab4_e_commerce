<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // List all users
    public function index()
{
    $currentUser = Auth::user(); 
    $admins = User::with('roles')->whereHas('roles', function ($query) {
        $query->where('role_name', 'Admin');
    })->orderBy('name')->get();

    $customers = User::with('roles')->whereHas('roles', function ($query) {
        $query->where('role_name', 'Customer');
    })->orderBy('name')->get(); 

    return view('products.users.index', compact('currentUser', 'admins', 'customers'));
}


    public function create()
{
    $roles = Role::all();

    return view('products.users.create', compact('roles'));
}


    // Store a new user
    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
        ]);

        $user = User::create([


            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

        ]);

        $user->roles()->sync($request->roles);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    
    public function edit(User $user)
    {
        
        $roles = Role::all(); 
    return view('products.users.edit', compact('user', 'roles'));
    }

    
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'roles' => 'required|array',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $user->roles()->sync($request->roles);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // Delete user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
