<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tractor;
use App\Models\Listing;
use App\Models\Request as TractorRequest;
use App\Models\Notification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(Request $request)
    {
        // Stats
        $stats = [
            'users' => User::count(),
            'tractors' => Tractor::count(),
            'activeListings' => Listing::where('is_active', true)->count(),
            'pendingRequests' => TractorRequest::where('status', 'pending')->count(),
        ];

        // Recent data
        $recentUsers = User::latest('created_at')->take(5)->get();
        $recentTractors = Tractor::latest('created_at')->take(5)->get();
        $recentListings = Listing::with(['tractor', 'seller'])
            ->latest('created_at')
            ->take(5)
            ->get();
        
        $recentRequests = TractorRequest::with(['listing', 'requester'])
            ->latest('created_at')
            ->take(5)
            ->get();
            
        $recentNotifications = [];
        if ($request->user()) {
            $recentNotifications = Notification::where('user_id', $request->user()->id)
                ->latest('created_at')
                ->take(5)
                ->get();
        }

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'recentTractors' => $recentTractors,
            'recentListings' => $recentListings,
            'recentRequests' => $recentRequests,
            'recentNotifications' => $recentNotifications,
        ]);
    }
}