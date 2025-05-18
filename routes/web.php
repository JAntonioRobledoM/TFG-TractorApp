<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Importar controladores desde el namespace Admin
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TractorController;
use App\Http\Controllers\Admin\ListingController as AdminListingController;
use App\Http\Controllers\Admin\RequestController as AdminRequestController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ConversationController;
use App\Http\Controllers\Admin\AperoController;
use App\Http\Controllers\Public\ContactController;

// Importar controlador público principal
use App\Http\Controllers\Public\HomeController;

// Importar controladores de User
use App\Http\Controllers\User\ListingController;
use App\Http\Controllers\User\RequestController;

use App\Models\User;
use App\Models\Tractor;
use App\Models\Listing as ListingModel;
use App\Models\Request as RequestModel;
use App\Models\Apero;

// Rutas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contacto', [ContactController::class, 'index'])->name('contact');
Route::post('/contacto', [ContactController::class, 'store']);

// Rutas de venta/alquiler públicas
Route::get('listings/sales', [AdminListingController::class, 'sales'])->name('listings.sales');
Route::get('listings/rentals', [AdminListingController::class, 'rentals'])->name('listings.rentals');

// Dashboard para usuarios normales (accesible a todos los usuarios autenticados)
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard para usuarios normales
    Route::get('user/dashboard', function () {
        return Inertia::render('User/Dashboard', [
            'userTractors' => auth()->user()->tractors()->latest()->take(5)->get(),
            'userListings' => auth()->user()->listings()->latest()->take(5)->get(),
            'userRequests' => auth()->user()->requests()->latest()->take(5)->get(),
        ]);
    })->name('user.dashboard');
    
    // Redirección inteligente para la ruta 'dashboard' según el rol del usuario
    Route::get('dashboard', function () {
        // Si el usuario es administrador, redirigir al dashboard admin
        if (auth()->user()->is_admin == 1) {
            return redirect()->route('admin.dashboard');
        }
        // Si es un usuario normal, redirigir al dashboard de usuario
        return redirect()->route('user.dashboard');
    })->name('dashboard');
    
    // Mensajes y notificaciones accesibles a todos los usuarios
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('notifications/recent', [NotificationController::class, 'recent'])->name('notifications.recent');
    Route::get('messages/unread-count', [MessageController::class, 'unreadCount'])->name('messages.unread-count');
    
    // Rutas de usuario para anuncios y solicitudes
    Route::prefix('user')->name('user.')->group(function () {
        // Rutas de anuncios
        Route::get('listings', [ListingController::class, 'index'])->name('listings.index');
        Route::get('listings/sales', [ListingController::class, 'sales'])->name('listings.sales');
        Route::get('listings/rentals', [ListingController::class, 'rentals'])->name('listings.rentals');
        Route::get('listings/{listing}', [ListingController::class, 'show'])->name('listings.show');
        
        // Rutas de solicitudes
        Route::get('requests', [RequestController::class, 'index'])->name('requests.index');
        Route::post('requests', [RequestController::class, 'store'])->name('requests.store');
        Route::get('requests/{request}', [RequestController::class, 'show'])->name('requests.show');
        Route::patch('requests/{request}/cancel', [RequestController::class, 'cancel'])->name('requests.cancel');
    });
});

// Rutas que requieren autenticación Y rol de administrador
Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard específico para administradores
    Route::get('admin/dashboard', function () {
        return Inertia::render('Admin/Dashboard', [ 
            'stats' => [
                'users' => User::count(),
                'tractors' => Tractor::count(),
                'aperos' => Apero::count(),
                'activeListings' => ListingModel::where('is_active', true)->count(),
                'pendingRequests' => RequestModel::where('status', 'pending')->count(),
            ],
            'recentUsers' => User::latest()->take(5)->get(),
            'recentTractors' => Tractor::latest()->take(5)->get(),
            'recentAperos' => Apero::latest()->take(5)->get(),
            'recentListings' => ListingModel::with('tractor')->latest()->take(5)->get(),
            'recentRequests' => RequestModel::with('requester')->latest()->take(5)->get(),
            'recentNotifications' => auth()->user()->notifications()->latest()->take(5)->get(),
        ]);
    })->name('admin.dashboard');
    
    // Rutas de gestión de usuarios
    Route::resource('users', UserController::class);
    Route::get('users/{user}/tractors', [UserController::class, 'tractors'])->name('users.tractors');
    Route::get('users/{user}/listings', [UserController::class, 'listings'])->name('users.listings');
    Route::get('users/{user}/assign-tractors', [UserController::class, 'assignTractors'])->name('users.assign-tractors');
    Route::post('users/{user}/add-tractor', [UserController::class, 'addTractor'])->name('users.add-tractor');
    Route::delete('users/{user}/remove-tractor/{tractor}', [UserController::class, 'removeTractor'])->name('users.remove-tractor');
    
    // Rutas de gestión de tractores
    Route::resource('tractors', TractorController::class);
    Route::get('tractors/{tractor}/listings', [TractorController::class, 'listings'])->name('tractors.listings');
    Route::get('tractors/{tractor}/users', [TractorController::class, 'users'])->name('tractors.users');
    Route::get('tractors/{tractor}/attach-aperos', [TractorController::class, 'attachAperos'])->name('tractors.attach-aperos');
    Route::post('tractors/{tractor}/attach-apero', [TractorController::class, 'attachApero'])->name('tractors.attach-apero');
    Route::delete('tractors/{tractor}/detach-apero/{apero}', [TractorController::class, 'detachApero'])->name('tractors.detach-apero');
    
    // Rutas de gestión de aperos
    Route::resource('aperos', AperoController::class);
    Route::get('aperos/{apero}/tractors', [AperoController::class, 'tractors'])->name('aperos.tractors');
    Route::get('aperos/{apero}/attach-tractors', [AperoController::class, 'attachTractors'])->name('aperos.attach-tractors');
    Route::post('aperos/{apero}/attach-tractor', [AperoController::class, 'attachTractor'])->name('aperos.attach-tractor');
    Route::delete('aperos/{apero}/detach-tractor/{tractor}', [AperoController::class, 'detachTractor'])->name('aperos.detach-tractor');
    
    // Rutas de gestión de anuncios
    Route::resource('listings', AdminListingController::class);
    Route::get('listings/{listing}/requests', [AdminListingController::class, 'requests'])->name('listings.requests');
    
    // Rutas de gestión de solicitudes
    Route::resource('requests', AdminRequestController::class);
    Route::patch('requests/{request}/accept', [AdminRequestController::class, 'accept'])->name('requests.accept');
    Route::patch('requests/{request}/reject', [AdminRequestController::class, 'reject'])->name('requests.reject');
    Route::patch('requests/{request}/complete', [AdminRequestController::class, 'complete'])->name('requests.complete');
    
    // Rutas de gestión de notificaciones
    Route::resource('notifications', NotificationController::class)->only(['index', 'show', 'destroy']);
    Route::post('notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::patch('notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    
    // Rutas de gestión de mensajes
    Route::resource('messages', MessageController::class)->only(['index', 'store', 'destroy']);
    Route::get('messages/{partnerId}', [MessageController::class, 'show'])->name('messages.show');
    Route::patch('messages/{senderId}/mark-as-read', [MessageController::class, 'markAsRead'])->name('messages.mark-as-read');
    Route::get('messages/contacts', [MessageController::class, 'contacts'])->name('messages.contacts');
    
    // Rutas de gestión de conversaciones
    Route::resource('conversations', ConversationController::class);
    Route::post('conversations/{conversation}/send-message', [ConversationController::class, 'sendMessage'])->name('conversations.send-message');
    Route::post('conversations/{conversation}/leave', [ConversationController::class, 'leave'])->name('conversations.leave');
    Route::post('conversations/{conversation}/add-users', [ConversationController::class, 'addUsers'])->name('conversations.add-users');
    Route::delete('conversations/{conversation}/users/{user}', [ConversationController::class, 'removeUser'])->name('conversations.remove-user');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';