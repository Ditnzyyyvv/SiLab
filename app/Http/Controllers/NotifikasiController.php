<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotifikasiController extends Controller
{
    /**
     * Tampilkan seluruh notifikasi pengguna (FR-28).
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $query = $user->notifications();

        if ($request->filter === 'unread') {
            $query = $user->unreadNotifications();
        }

        $notifikasis = $query->paginate(15)->withQueryString();
        $unreadCount = $user->unreadNotifications()->count();

        return view('notifikasi.index', compact('notifikasis', 'unreadCount'));
    }

    /**
     * Tandai notifikasi spesifik telah dibaca dan arahkan ke halaman terkait.
     */
    public function read(string $id): RedirectResponse
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $targetUrl = $notification->data['url'] ?? route('dashboard');

        return redirect()->to($targetUrl);
    }

    /**
     * Tandai seluruh notifikasi yang belum dibaca menjadi dibaca.
     */
    public function markAllRead(): RedirectResponse
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}

