<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Importar controladores desde el namespace Admin
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TractorController;
use App\Http\Controllers\Admin\ListingController;
use App\Http\Controllers\Admin\RequestController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ConversationController;
use App\Http\Controllers\Admin\AperoController;

use App\Models\User;
use App\Models\Tractor;
use App\Models\Listing;
use App\Models\Request as RequestModel;
use App\Models\Apero;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Admin/Dashboard', [ 
        'stats' => [
            'users' => User::count(),
            'tractors' => Tractor::count(),
            'aperos' => Apero::count(),
            'activeListings' => Listing::where('is_active', true)->count(),
            'pendingRequests' => RequestModel::where('status', 'pending')->count(),
        ],
        'recentUsers' => User::latest()->take(5)->get(),
        'recentTractors' => Tractor::latest()->take(5)->get(),
        'recentAperos' => Apero::latest()->take(5)->get(),
        'recentListings' => Listing::with('tractor')->latest()->take(5)->get(),
        'recentRequests' => RequestModel::with('requester')->latest()->take(5)->get(),
        'recentNotifications' => auth()->user()->notifications()->latest()->take(5)->get(),
    ]);
})
->middleware(['auth', 'verified'])
->name('dashboard');

// Rutas públicas
Route::get('listings/sales', [ListingController::class, 'sales'])->name('listings.sales');
Route::get('listings/rentals', [ListingController::class, 'rentals'])->name('listings.rentals');

// Rutas que requieren autenticación
Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('tractors', TractorController::class);
    Route::resource('aperos', AperoController::class);
    Route::resource('listings', ListingController::class);
    Route::resource('requests', RequestController::class);
    Route::resource('notifications', NotificationController::class)->only(['index', 'show', 'destroy']);
    Route::resource('messages', MessageController::class)->only(['index', 'store', 'destroy']);
    Route::resource('conversations', ConversationController::class);

    Route::get('users/{user}/tractors', [UserController::class, 'tractors'])->name('users.tractors');
    Route::get('users/{user}/listings', [UserController::class, 'listings'])->name('users.listings');

    Route::get('tractors/{tractor}/listings', [TractorController::class, 'listings'])->name('tractors.listings');
    Route::get('tractors/{tractor}/users', [TractorController::class, 'users'])->name('tractors.users');

    Route::get('aperos/{apero}/tractors', [AperoController::class, 'tractors'])->name('aperos.tractors');
    Route::get('aperos/{apero}/attach-tractors', [AperoController::class, 'attachTractors'])->name('aperos.attach-tractors');
    Route::post('aperos/{apero}/attach-tractor', [AperoController::class, 'attachTractor'])->name('aperos.attach-tractor');
    Route::delete('aperos/{apero}/detach-tractor/{tractor}', [AperoController::class, 'detachTractor'])->name('aperos.detach-tractor');

    Route::get('tractors/{tractor}/attach-aperos', [TractorController::class, 'attachAperos'])->name('tractors.attach-aperos');
    Route::post('tractors/{tractor}/attach-apero', [TractorController::class, 'attachApero'])->name('tractors.attach-apero');
    Route::delete('tractors/{tractor}/detach-apero/{apero}', [TractorController::class, 'detachApero'])->name('tractors.detach-apero');

    Route::get('listings/{listing}/requests', [ListingController::class, 'requests'])->name('listings.requests');

    Route::patch('requests/{request}/accept', [RequestController::class, 'accept'])->name('requests.accept');
    Route::patch('requests/{request}/reject', [RequestController::class, 'reject'])->name('requests.reject');
    Route::patch('requests/{request}/complete', [RequestController::class, 'complete'])->name('requests.complete');

    Route::post('notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::patch('notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('notifications/recent', [NotificationController::class, 'recent'])->name('notifications.recent');

    Route::get('messages/{partnerId}', [MessageController::class, 'show'])->name('messages.show');
    Route::get('messages/unread-count', [MessageController::class, 'unreadCount'])->name('messages.unread-count');
    Route::patch('messages/{senderId}/mark-as-read', [MessageController::class, 'markAsRead'])->name('messages.mark-as-read');
    Route::get('messages/contacts', [MessageController::class, 'contacts'])->name('messages.contacts');

    Route::post('conversations/{conversation}/send-message', [ConversationController::class, 'sendMessage'])->name('conversations.send-message');
    Route::post('conversations/{conversation}/leave', [ConversationController::class, 'leave'])->name('conversations.leave');
    Route::post('conversations/{conversation}/add-users', [ConversationController::class, 'addUsers'])->name('conversations.add-users');
    Route::delete('conversations/{conversation}/users/{user}', [ConversationController::class, 'removeUser'])->name('conversations.remove-user');

    Route::get('users/{user}/assign-tractors', [UserController::class, 'assignTractors'])->name('users.assign-tractors');
    Route::post('users/{user}/add-tractor', [UserController::class, 'addTractor'])->name('users.add-tractor');
    Route::delete('users/{user}/remove-tractor/{tractor}', [UserController::class, 'removeTractor'])->name('users.remove-tractor');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
