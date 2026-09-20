<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Http\Requests\Login\RegisterRequest;

class RegisterController extends Controller
{
    public function index()
    {
        return view('login.register');
    }

    public function store(RegisterRequest $request)
    {
        $member = Role::where('name', 'member')->first();

        // create user
        $user = User::create([
            'role_id' => $member->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        

        Auth::login($user);

        return redirect()->intended(route('home'))->with('success', 'Akun berhasil dibuat. Selamat datang!');
    }
}
