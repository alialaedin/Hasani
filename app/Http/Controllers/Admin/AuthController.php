<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
	public function showLoginForm(): View
	{
		return view("auth.login");
	}

	public function login(Request $request): RedirectResponse
	{
		if (Auth::guard('web')->attempt($request->except("_token"))) {
			$request->session()->regenerate();
			flash()->success('با موفقیت وارد شدید');
			return redirect()->intended('dashboard');
		}

		return back()->withErrors([
			'mobile' => 'چنین شماره ای در پایگاه داده ثبت نشده است.',
		])->onlyInput('mobile');
	}

	public function logout(Request $request): RedirectResponse
	{
		$request->session()->invalidate();
		$request->session()->regenerateToken();
		return redirect()->route("login-form");
	}
}
