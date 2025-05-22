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
        $user = auth()->user();
        $availableListings = ListingModel::with(['tractor', 'seller'])
            ->where('is_active', true)
            ->where('seller_id', '!=', $user->id)
            ->latest()
            ->get();

        return Inertia::render('User/Dashboard', [
            'userTractors' => $user->tractors()->latest()->take(5)->get(),
            'userListings' => $user->listings()->with(['tractor'])->latest()->take(5)->get(),
            'userRequests' => $user->requests()->with(['listing.tractor'])->latest()->take(5)->get(),
            'availableListings' => $availableListings
        ]);
    })->name('user.dashboard');

    // API para obtener anuncios disponibles
    Route::get('user/api/available-listings', function () {
        $user = auth()->user();
        $listings = ListingModel::with(['tractor', 'seller'])
            ->where('is_active', true)
            ->where('seller_id', '!=', $user->id)
            ->latest()
            ->get();

        return response()->json(['listings' => $listings]);
    })->name('user.api.available-listings');

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
        // Rutas para tractores
        Route::get('tractors', function () {
            return Inertia::render('User/Tractors/Index', [
                'tractors' => auth()->user()->tractors()->latest()->get()
            ]);
        })->name('tractors.index');

        Route::get('tractors/{tractor}', function (App\Models\Tractor $tractor) {
            // Verificar que el usuario es propietario del tractor
            if (!$tractor->owners()->where('user_id', auth()->id())->exists()) {
                abort(403, 'No tienes permiso para ver este tractor.');
            }

            return Inertia::render('User/Tractors/Show', [
                'tractor' => $tractor
            ]);
        })->name('tractors.show');

        Route::post('tractors', function (Illuminate\Http\Request $request) {
            $validated = $request->validate([
                'brand' => ['nullable', 'string', 'max:255'],
                'model' => ['nullable', 'string', 'max:255'],
                'year' => ['nullable', 'integer'],
                'description' => ['nullable', 'string'],
                'horsepower' => ['nullable', 'integer'],
                'working_hours' => ['nullable', 'numeric'],
                'is_available' => ['boolean'],
            ]);

            // Crear el tractor
            $tractor = \App\Models\Tractor::create($validated);

            // Asignar al usuario actual
            $tractor->owners()->attach(auth()->id());

            return redirect()->route('user.dashboard')
                ->with('message', 'Tractor creado correctamente.');
        })->name('tractors.store');

        // Nueva ruta para editar tractores
        Route::put('tractors/{tractor}', function (Illuminate\Http\Request $request, App\Models\Tractor $tractor) {
            // Verificar que el usuario es propietario del tractor
            if (!$tractor->owners()->where('user_id', auth()->id())->exists()) {
                abort(403, 'No tienes permiso para editar este tractor.');
            }

            $validated = $request->validate([
                'brand' => ['nullable', 'string', 'max:255'],
                'model' => ['nullable', 'string', 'max:255'],
                'year' => ['nullable', 'integer'],
                'description' => ['nullable', 'string'],
                'horsepower' => ['nullable', 'integer'],
                'working_hours' => ['nullable', 'numeric'],
                'is_available' => ['boolean'],
            ]);

            $tractor->update($validated);

            return redirect()->route('user.dashboard')
                ->with('message', 'Tractor actualizado correctamente.');
        })->name('tractors.update');

        Route::delete('tractors/{tractor}', function (App\Models\Tractor $tractor) {
            // Verificar que el usuario es propietario del tractor
            if (!$tractor->owners()->where('user_id', auth()->id())->exists()) {
                abort(403, 'No tienes permiso para eliminar este tractor.');
            }

            // Verificar si el tractor está siendo usado en algún anuncio activo
            if ($tractor->listings()->where('is_active', true)->exists()) {
                return back()->with('error', 'No puedes eliminar este tractor porque está siendo usado en anuncios activos.');
            }

            // Eliminar las relaciones con el usuario actual
            $tractor->owners()->detach(auth()->id());

            // Si no hay más propietarios, eliminar el tractor
            if ($tractor->owners()->count() === 0) {
                $tractor->delete();
            }

            return redirect()->route('user.dashboard')
                ->with('message', 'Tractor eliminado correctamente.');
        })->name('tractors.destroy');

        // Rutas de anuncios
        Route::get('listings', [ListingController::class, 'index'])->name('listings.index');
        Route::get('listings/sales', [ListingController::class, 'sales'])->name('listings.sales');
        Route::get('listings/rentals', [ListingController::class, 'rentals'])->name('listings.rentals');
        Route::get('listings/{listing}', [ListingController::class, 'show'])->name('listings.show');

        // Ruta para crear anuncios
        Route::post('listings', function (Illuminate\Http\Request $request) {
            $validated = $request->validate([
                'tractor_id' => ['required', 'exists:tractors,id'],
                'type' => ['required', 'string', 'in:sale,rental'],
                'price' => ['required', 'numeric', 'min:0'],
                'description' => ['nullable', 'string'],
                'is_active' => ['boolean'],
                'start_date' => ['nullable', 'date', 'required_if:type,rental'],
                'end_date' => ['nullable', 'date', 'required_if:type,rental', 'after_or_equal:start_date'],
            ]);

            // Verificar que el usuario es propietario del tractor
            $tractor = \App\Models\Tractor::find($validated['tractor_id']);
            if (!$tractor) {
                return back()->withErrors(['tractor_id' => 'El tractor seleccionado no existe.']);
            }

            // Verificar si el usuario posee el tractor
            if (!$tractor->owners()->where('user_id', auth()->id())->exists()) {
                return back()->withErrors(['tractor_id' => 'No tienes permiso para publicar este tractor.']);
            }

            // Agregar el ID del usuario actual
            $validated['seller_id'] = auth()->id();

            // Crear el anuncio
            $listing = \App\Models\Listing::create($validated);

            return redirect()->route('user.dashboard')
                ->with('message', 'Anuncio creado correctamente.');
        })->name('listings.store');

        // Nueva ruta para editar anuncios
        Route::put('listings/{listing}', function (Illuminate\Http\Request $request, App\Models\Listing $listing) {
            // Verificar que el usuario es propietario del anuncio
            if ($listing->seller_id !== auth()->id()) {
                abort(403, 'No tienes permiso para modificar este anuncio.');
            }

            $validated = $request->validate([
                'tractor_id' => ['required', 'exists:tractors,id'],
                'type' => ['required', 'string', 'in:sale,rental'],
                'price' => ['required', 'numeric', 'min:0'],
                'description' => ['nullable', 'string'],
                'is_active' => ['boolean'],
                'start_date' => ['nullable', 'date', 'required_if:type,rental'],
                'end_date' => ['nullable', 'date', 'required_if:type,rental', 'after_or_equal:start_date'],
            ]);

            // Verificar que el usuario es propietario del tractor
            $tractor = \App\Models\Tractor::find($validated['tractor_id']);
            if (!$tractor->owners()->where('user_id', auth()->id())->exists()) {
                return back()->withErrors(['tractor_id' => 'No tienes permiso para usar este tractor.']);
            }

            $listing->update($validated);

            return back()->with('message', 'Anuncio actualizado correctamente.');
        })->name('listings.update');

        // Rutas de solicitudes
        Route::get('requests', [RequestController::class, 'index'])->name('requests.index');

        // Modificada la ruta para crear solicitudes (corregido el error con el campo 'type')
        Route::post('requests', function (Illuminate\Http\Request $request) {
            $validated = $request->validate([
                'listing_id' => ['required', 'exists:listings,id'],
                'message' => ['nullable', 'string'],
                'offered_price' => ['nullable', 'numeric', 'min:0'],
            ]);

            // Obtener el listing para determinar el tipo
            $listing = \App\Models\Listing::findOrFail($validated['listing_id']);

            // Check if the user is trying to request their own listing
            if ($listing->seller_id === auth()->id()) {
                return back()->with('error', 'No puedes solicitar tu propio anuncio.');
            }

            // Check if the user already has a request for this listing
            $existingRequest = \App\Models\Request::where('requester_id', auth()->id())
                ->where('listing_id', $validated['listing_id'])
                ->first();

            if ($existingRequest) {
                return back()->with('error', 'Ya has enviado una solicitud para este anuncio.');
            }

            // Si no se proporciona el precio ofrecido, usar el precio del anuncio
            if (!isset($validated['offered_price']) || $validated['offered_price'] == '') {
                $validated['offered_price'] = $listing->price;
            }

            // Crear la solicitud
            $newRequest = \App\Models\Request::create([
                'requester_id' => auth()->id(),
                'listing_id' => $validated['listing_id'],
                'message' => $validated['message'] ?? null,
                'type' => $listing->type, // Usar el mismo tipo que el anuncio (sale o rental)
                'status' => 'pending',
                'offered_price' => $validated['offered_price'],
                // Para solicitudes de alquiler, usar las fechas del anuncio por defecto
                'requested_start_date' => $listing->type === 'rental' ? $listing->start_date : null,
                'requested_end_date' => $listing->type === 'rental' ? $listing->end_date : null,
            ]);

            return back()->with('message', 'Solicitud enviada correctamente.');
        })->name('requests.store');

        Route::get('requests/{request}', [RequestController::class, 'show'])->name('requests.show');
        Route::patch('requests/{request}/cancel', [RequestController::class, 'cancel'])->name('requests.cancel');

        // Ruta para activar/desactivar un anuncio
        Route::put('listings/{listing}/toggle-status', function (App\Models\Listing $listing) {
            // Verificar que el usuario es propietario del anuncio
            if ($listing->seller_id !== auth()->id()) {
                abort(403, 'No tienes permiso para modificar este anuncio.');
            }

            // Cambiar el estado
            $listing->update([
                'is_active' => !$listing->is_active
            ]);

            return back()->with('message', 'El estado del anuncio se ha actualizado correctamente.');
        })->name('listings.toggle-status');

        // Rutas para aceptar/rechazar/completar solicitudes
        Route::put('requests/{request}/accept', function (App\Models\Request $request) {
            // Verificar que el usuario es el propietario del anuncio
            $listing = $request->listing;
            if (!$listing || $listing->seller_id !== auth()->id()) {
                abort(403, 'No tienes permiso para aceptar esta solicitud.');
            }

            // Verificar que la solicitud está pendiente
            if ($request->status !== 'pending') {
                return back()->with('error', 'Solo puedes aceptar solicitudes pendientes.');
            }

            // Actualizar el estado de la solicitud
            $request->update([
                'status' => 'accepted'
            ]);

            return back()->with('message', 'Solicitud aceptada correctamente.');
        })->name('requests.accept');

        Route::put('requests/{request}/reject', function (App\Models\Request $request) {
            // Verificar que el usuario es el propietario del anuncio
            $listing = $request->listing;
            if (!$listing || $listing->seller_id !== auth()->id()) {
                abort(403, 'No tienes permiso para rechazar esta solicitud.');
            }

            // Verificar que la solicitud está pendiente
            if ($request->status !== 'pending') {
                return back()->with('error', 'Solo puedes rechazar solicitudes pendientes.');
            }

            // Actualizar el estado de la solicitud
            $request->update([
                'status' => 'rejected'
            ]);

            return back()->with('message', 'Solicitud rechazada correctamente.');
        })->name('requests.reject');

        Route::put('requests/{request}/complete', function (App\Models\Request $request) {
            // Verificar que el usuario es el propietario del anuncio o el solicitante
            $listing = $request->listing;
            if (!$listing || ($listing->seller_id !== auth()->id() && $request->requester_id !== auth()->id())) {
                abort(403, 'No tienes permiso para completar esta solicitud.');
            }

            // Verificar que la solicitud está aceptada
            if ($request->status !== 'accepted') {
                return back()->with('error', 'Solo puedes completar solicitudes aceptadas.');
            }

            // Actualizar el estado de la solicitud
            $request->update([
                'status' => 'completed'
            ]);

            // Si es una solicitud de compra, desactivar el anuncio
            if ($request->type === 'sale') {
                $listing->update([
                    'is_active' => false
                ]);
            }

            return back()->with('message', 'Solicitud marcada como completada correctamente.');
        })->name('requests.complete');

        // Ruta para eliminar un anuncio
        Route::delete('listings/{listing}', function (App\Models\Listing $listing) {
            // Verificar que el usuario es propietario del anuncio
            if ($listing->seller_id !== auth()->id()) {
                abort(403, 'No tienes permiso para eliminar este anuncio.');
            }

            $listing->delete();

            return redirect()->route('user.dashboard')
                ->with('message', 'Anuncio eliminado correctamente.');
        })->name('listings.destroy');

        // Ruta para eliminar una solicitud
        Route::delete('requests/{request}', function (App\Models\Request $request) {
            // Verificar que el usuario es el propietario de la solicitud
            if ($request->requester_id !== auth()->id()) {
                abort(403, 'No tienes permiso para eliminar esta solicitud.');
            }

            $request->delete();

            return redirect()->route('user.dashboard')
                ->with('message', 'Solicitud eliminada correctamente.');
        })->name('requests.destroy');
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

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';