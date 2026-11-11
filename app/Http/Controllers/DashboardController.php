<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    /**
     * Redirect authenticated users to their role-specific dashboard.
     */
    public function index(Request $request): RedirectResponse
    {
        return match ($request->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'teknisi' => redirect()->route('teknisi.dashboard'),
            'member' => redirect()->route('member.dashboard'),
            default => abort(403, 'Role pengguna tidak dikenali.'),
        };
    }

    /**
     * Admin Dashboard view.
     */
    public function admin(): View
    {
        return view('admin.dashboard');
    }

    /**
     * Teknisi Dashboard view.
     */
    public function teknisi(): View
    {
        return view('teknisi.dashboard');
    }

    /**
     * Member Dashboard view.
     */
    public function member(): View
    {
        return view('member.dashboard');
    }
}
