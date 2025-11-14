<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'desc')
            ->paginate(20);
            
        $unreadCount = Notification::unread()->count();
        
        return view('pages.notifications.index', compact('notifications', 'unreadCount'));
    }
    
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }
    
    public function markAllAsRead()
    {
        Notification::unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'All notifications marked as read');
    }
    
    public function getUnreadCount()
    {
        return response()->json([
            'count' => Notification::unread()->count()
        ]);
    }
}
