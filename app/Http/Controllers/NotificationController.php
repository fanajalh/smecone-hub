<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->appNotifications()
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($notif) {
                return $notif->created_at->translatedFormat('d F Y');
            });

        // Mark all as read
        Auth::user()->appNotifications()->unread()->update(['read_at' => now()]);

        return view('notifikasi', compact('notifications'));
    }

    public function markAllRead()
    {
        Auth::user()->appNotifications()->unread()->update(['read_at' => now()]);
        return back()->with('success', 'Semua notifikasi ditandai telah dibaca.');
    }

    public function destroy($id)
    {
        $notification = Auth::user()->appNotifications()->findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function clearAll()
    {
        Auth::user()->appNotifications()->delete();
        return back()->with('success', 'Semua riwayat notifikasi dikosongkan.');
    }

    public function unreadCount()
    {
        return response()->json([
            'unread_count' => Auth::user()->unread_notifications_count
        ]);
    }
}
