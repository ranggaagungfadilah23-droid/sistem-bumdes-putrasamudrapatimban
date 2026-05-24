<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * Menampilkan daftar notifikasi
     */
    public function index()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->latest()->get();

        // Tandai semua notifikasi sebagai sudah dibaca saat halaman dibuka
        $user->unreadNotifications->markAsRead();

        return view('notifications.index', compact('notifications'));
    }


    public function customer()
{
    // Pastikan ini mengarah ke model User dengan foreign key yang tepat
    return $this->belongsTo(User::class, 'customer_id', 'id');
}
    /**
     * Menghapus satu notifikasi (Dipanggil via Fetch/AJAX di JavaScript)
     */
    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Menghapus semua notifikasi sekaligus
     */
    public function destroyAll()
    {
        auth()->user()->notifications()->delete();

        return back()->with('success', 'Semua notifikasi berhasil dihapus.');
    }
}
