<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function adminDashboard(Request $request) {

        $data = [
            'pageTitle' => 'Admin Dashboard',
            'user' => User::findOrFail(auth()->id())
        ];
        return view('back.pages.dashboard', $data);
    }

    public function logoutHandler(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('fail', 'You are now logged out');
    }
}
