<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return Inertia::render('Notifications/Index', [
            'notifications' => $notifications
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        // Mark as read if not already
        if (!$notification->is_read) {
            $notification->update(['is_read' => true]);
        }
        
        // Load related model based on related_type
        switch ($notification->related_type) {
            case 'request':
                $notification->load(['related' => function($query) {
                    $query->with(['listing.tractor', 'requester']);
                }]);
                break;
            case 'message':
                $notification->load(['related' => function($query) {
                    $query->with(['sender', 'receiver']);
                }]);
                break;
            default:
                // No additional loading
                break;
        }
        
        return Inertia::render('Notifications/Show', [
            'notification' => $notification
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();

        return redirect()->route('notifications.index')
            ->with('message', 'Notificación eliminada correctamente.');
    }
    
    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return redirect()->route('notifications.index')
            ->with('message', 'Todas las notificaciones marcadas como leídas.');
    }
    
    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);
        
        return redirect()->back()
            ->with('message', 'Notificación marcada como leída.');
    }
    
    /**
     * Get unread notifications count.
     */
    public function unreadCount(Request $request)
    {
        $user = $request->user();
        $count = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
            
        return response()->json(['count' => $count]);
    }
    
    /**
     * Get recent notifications.
     */
    public function recent(Request $request)
    {
        $user = $request->user();
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        return response()->json(['notifications' => $notifications]);
    }
}