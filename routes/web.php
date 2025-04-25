<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TractorController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ConversationController;
use App\Models\User;
use App\Models\Tractor;
use App\Models\Listing;
use App\Models\Request as RequestModel;

// Redirigir la raíz al dashboard si está autenticado, o a la página de inicio de sesión si no
Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

// El dashboard con estadísticas
Route::get('dashboard', function () {
    return Inertia::render('Dashboard', [
        'stats' => [
            'users' => User::count(),
            'tractors' => Tractor::count(),
            'activeListings' => Listing::where('is_active', true)->count(),
            'pendingRequests' => RequestModel::where('status', 'pending')->count(),
        ],
        'recentUsers' => User::latest()->take(5)->get(),
        'recentTractors' => Tractor::latest()->take(5)->get(),
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
    // Rutas de recursos básicos
    Route::resource('users', UserController::class);
    Route::resource('tractors', TractorController::class);
    Route::resource('listings', ListingController::class);
    Route::resource('requests', RequestController::class);
    Route::resource('notifications', NotificationController::class)->only(['index', 'show', 'destroy']);
    Route::resource('messages', MessageController::class)->only(['index', 'store', 'destroy']);
    Route::resource('conversations', ConversationController::class);
    
    // Rutas adicionales para usuarios
    Route::get('users/{user}/tractors', [UserController::class, 'tractors'])->name('users.tractors');
    Route::get('users/{user}/listings', [UserController::class, 'listings'])->name('users.listings');
    
    // Rutas adicionales para tractores
    Route::get('tractors/{tractor}/listings', [TractorController::class, 'listings'])->name('tractors.listings');
    Route::get('tractors/{tractor}/users', [TractorController::class, 'users'])->name('tractors.users');
    
    // Rutas adicionales para anuncios
    Route::get('listings/{listing}/requests', [ListingController::class, 'requests'])->name('listings.requests');
    
    // Rutas adicionales para solicitudes
    Route::patch('requests/{request}/accept', [RequestController::class, 'accept'])->name('requests.accept');
    Route::patch('requests/{request}/reject', [RequestController::class, 'reject'])->name('requests.reject');
    Route::patch('requests/{request}/complete', [RequestController::class, 'complete'])->name('requests.complete');
    
    // Rutas adicionales para notificaciones
    Route::post('notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::patch('notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('notifications/recent', [NotificationController::class, 'recent'])->name('notifications.recent');
    
    // Rutas adicionales para mensajes
    Route::get('messages/{partnerId}', [MessageController::class, 'show'])->name('messages.show');
    Route::get('messages/unread-count', [MessageController::class, 'unreadCount'])->name('messages.unread-count');
    Route::patch('messages/{senderId}/mark-as-read', [MessageController::class, 'markAsRead'])->name('messages.mark-as-read');
    Route::get('messages/contacts', [MessageController::class, 'contacts'])->name('messages.contacts');
    
    // Rutas adicionales para conversaciones
    Route::post('conversations/{conversation}/send-message', [ConversationController::class, 'sendMessage'])->name('conversations.send-message');
    Route::post('conversations/{conversation}/leave', [ConversationController::class, 'leave'])->name('conversations.leave');
    Route::post('conversations/{conversation}/add-users', [ConversationController::class, 'addUsers'])->name('conversations.add-users');
    Route::delete('conversations/{conversation}/users/{user}', [ConversationController::class, 'removeUser'])->name('conversations.remove-user');

    // Rutas para gestión de tractores de usuarios
    Route::get('users/{user}/assign-tractors', [UserController::class, 'assignTractors'])
    ->name('users.assign-tractors');
    Route::post('users/{user}/add-tractor', [UserController::class, 'addTractor'])
        ->name('users.add-tractor');
    Route::delete('users/{user}/remove-tractor/{tractor}', [UserController::class, 'removeTractor'])
        ->name('users.remove-tractor');
});

Route::middleware(['auth'])->group(function () {
    // Rutas de solicitudes existentes
    Route::resource('requests', RequestController::class);
    
    // Rutas adicionales para acciones específicas
    Route::post('/requests/{request}/accept', [RequestController::class, 'accept'])->name('requests.accept');
    Route::post('/requests/{request}/reject', [RequestController::class, 'reject'])->name('requests.reject');
    Route::post('/requests/{request}/complete', [RequestController::class, 'complete'])->name('requests.complete');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';