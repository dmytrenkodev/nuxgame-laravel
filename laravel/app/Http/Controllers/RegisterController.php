<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function showForm(): Factory|View
    {
        return view('register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
        ]);

        $token = User::generateToken();

        User::create([
            'username' => $request->username,
            'phone' => $request->phone,
            'token' => $token,
            'expires_at' => now()->addDays(7),
            'active' => true,
        ]);

        return redirect()->route('lucky.page', ['token' => $token]);
    }
}
