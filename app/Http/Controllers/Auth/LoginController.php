<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\Login\LoginRequest;


class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('login.index');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LoginRequest $request)
    {
        //
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email atau password salah']);
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Email atau password salah']);
        }

        Auth::login($user);

        $defaultRoute = $user->role->name === 'admin'
            ? route('product.index')
            : ($user->role->name === 'cashier' ? route('cashier.index') : route('home'));

        return redirect()->intended($defaultRoute);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
