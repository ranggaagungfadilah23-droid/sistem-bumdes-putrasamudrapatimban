<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Ubah dari 'class Notifications' menjadi 'class NotificationsController'
class NotificationsController extends Controller
{
  public function index() {
    $user = auth()->user();
    $notifications = $user->notifications()->latest()->get();

    // Tandai semua notifikasi sebagai sudah dibaca saat halaman dibuka
    $user->unreadNotifications->markAsRead();

    return view('notifications.index', compact('notifications'));
}
}
