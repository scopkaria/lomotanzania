<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(30);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function fetch(): JsonResponse
    {
        $notifications = Auth::user()->unreadNotifications->take(10)->map(fn ($n) => [
            'id' => $n->id,
            'type' => class_basename($n->type),
            'title' => $n->data['title'] ?? 'Notification',
            'message' => $n->data['message'] ?? '',
            'url' => $n->data['url'] ?? '#',
            'icon' => $n->data['icon'] ?? 'bell',
            'time' => $n->created_at->diffForHumans(),
        ]);

        return response()->json(['notifications' => $notifications]);
    }

    public function markAsRead(string $id): JsonResponse
    {
        Auth::user()->notifications()->where('id', $id)->update(['read_at' => now()]);
        return response()->json(['ok' => true]);
    }

    public function markAllRead(): JsonResponse
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['ok' => true]);
    }
}
