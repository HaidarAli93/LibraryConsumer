<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
	public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Check if user exists
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Redirect to registration if the user doesn't exist
			return redirect()
				->route('login')
				->with('toast', [
					'type' => 'danger',
					'message' => 'User not found.',
				]);
        }

        // Attempt to log in the user
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
			return redirect()->route('dashboard'); // Admin dashboard
        }

        // If login fails
        //return back()->withErrors(['email' => 'Invalid email or password']);
		return back()->with('toast', [
			'type' => 'danger',
			'message' => 'Invalid email or password',
		]);
    }

	public function logout(Request $request)
	{
		Auth::logout();
		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return redirect()->route('login');
	}
}
