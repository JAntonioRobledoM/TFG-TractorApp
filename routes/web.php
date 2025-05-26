<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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
use App\Http\Controllers\ChatController;

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

    // Dentro del grupo de rutas de usuario, agregar esta nueva ruta
    Route::put('tractors/{tractor}/update-hours', function (Illuminate\Http\Request $request, App\Models\Tractor $tractor) {
        // Verificar que el usuario es propietario del tractor
        if (!$tractor->owners()->where('user_id', auth()->id())->exists()) {
            abort(403, 'No tienes permiso para editar este tractor.');
        }

        $validated = $request->validate([
            'working_hours' => ['required', 'numeric', 'min:0'],
        ]);

        $tractor->update([
            'working_hours' => $validated['working_hours'],
        ]);

        return back()->with('message', 'Horas de trabajo actualizadas correctamente.');
    })->name('user.tractors.update-hours');

    // Dashboard para usuarios normales
    Route::get('user/dashboard', function () {
        $user = auth()->user();

        // Cargar anuncios disponibles con todas las relaciones necesarias
        $availableListings = ListingModel::with(['tractor.owners', 'seller'])
            ->where('is_active', true)
            ->where('seller_id', '!=', $user->id)
            ->latest()
            ->get();

        // Obtener solicitudes recibidas en mis anuncios
        $receivedRequests = RequestModel::whereHas('listing', function ($query) use ($user) {
            $query->where('seller_id', $user->id);
        })
            ->with(['listing.tractor', 'requester'])
            ->latest()
            ->take(10) // Limitar a las 10 más recientes
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'type' => $request->type,
                    'status' => $request->status,
                    'offered_price' => $request->offered_price,
                    'message' => $request->message,
                    'created_at' => $request->created_at,
                    'listing_id' => $request->listing_id,
                    'requester_id' => $request->requester_id,
                    'listing' => $request->listing ? [
                        'id' => $request->listing->id,
                        'type' => $request->listing->type,
                        'price' => $request->listing->price,
                        'tractor' => $request->listing->tractor ? [
                            'id' => $request->listing->tractor->id,
                            'brand' => $request->listing->tractor->brand,
                            'model' => $request->listing->tractor->model,
                        ] : null,
                    ] : null,
                    'requester' => $request->requester ? [
                        'id' => $request->requester->id,
                        'first_name' => $request->requester->first_name,
                        'last_name' => $request->requester->last_name,
                    ] : null,
                ];
            });

        return Inertia::render('User/Dashboard', [
            // Cargar tractores con información completa
            'userTractors' => $user->tractors()
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($tractor) {
                    return [
                        'id' => $tractor->id,
                        'brand' => $tractor->brand,
                        'model' => $tractor->model,
                        'year' => $tractor->year,
                        'horsepower' => $tractor->horsepower,
                        'working_hours' => $tractor->working_hours,
                        'is_available' => $tractor->is_available,
                        'description' => $tractor->description,
                        'image' => $tractor->image,
                        'image_url' => $tractor->image_url,
                    ];
                }),

            'userAperos' => $user->tractors()
                ->with('aperos')
                ->get()
                ->pluck('aperos')
                ->flatten()
                ->unique('id')
                ->values()
                ->map(function ($apero) {
                    return [
                        'id' => $apero->id,
                        'name' => $apero->name,
                        'type' => $apero->type,
                        'brand' => $apero->brand,
                        'model' => $apero->model,
                        'year' => $apero->year,
                        'description' => $apero->description,
                        'is_available' => $apero->is_available,
                        'image' => $apero->image,
                        'image_url' => $apero->image_url,
                    ];
                }),

            // Cargar anuncios con información completa del tractor y vendedor
            'userListings' => $user->listings()
                ->with(['tractor'])
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($listing) {
                    return [
                        'id' => $listing->id,
                        'type' => $listing->type,
                        'price' => $listing->price,
                        'description' => $listing->description,
                        'is_active' => $listing->is_active,
                        'start_date' => $listing->start_date,
                        'end_date' => $listing->end_date,
                        'created_at' => $listing->created_at,
                        'tractor' => $listing->tractor ? [
                            'id' => $listing->tractor->id,
                            'brand' => $listing->tractor->brand,
                            'model' => $listing->tractor->model,
                            'year' => $listing->tractor->year,
                            'horsepower' => $listing->tractor->horsepower,
                            'image_url' => $listing->tractor->image_url,
                        ] : null,
                        // Cargar solicitudes recibidas para este anuncio
                        'requests' => $listing->requests()
                            ->with('requester')
                            ->latest()
                            ->get()
                            ->map(function ($request) {
                                return [
                                    'id' => $request->id,
                                    'status' => $request->status,
                                    'offered_price' => $request->offered_price,
                                    'created_at' => $request->created_at,
                                    'requester' => $request->requester ? [
                                        'id' => $request->requester->id,
                                        'first_name' => $request->requester->first_name,
                                        'last_name' => $request->requester->last_name,
                                    ] : null,
                                ];
                            }),
                    ];
                }),

            // Cargar solicitudes con información completa (las que YO he enviado)
            'userRequests' => $user->requests()
                ->with(['listing.tractor', 'listing.seller'])
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($request) {
                    return [
                        'id' => $request->id,
                        'type' => $request->type ?? $request->listing->type,
                        'status' => $request->status,
                        'offered_price' => $request->offered_price,
                        'message' => $request->message,
                        'created_at' => $request->created_at,
                        'listing_id' => $request->listing_id,
                        'listing' => $request->listing ? [
                            'id' => $request->listing->id,
                            'type' => $request->listing->type,
                            'price' => $request->listing->price,
                            'tractor' => $request->listing->tractor ? [
                                'id' => $request->listing->tractor->id,
                                'brand' => $request->listing->tractor->brand,
                                'model' => $request->listing->tractor->model,
                            ] : null,
                            'seller' => $request->listing->seller ? [
                                'id' => $request->listing->seller->id,
                                'first_name' => $request->listing->seller->first_name,
                                'last_name' => $request->listing->seller->last_name,
                            ] : null,
                        ] : null,
                    ];
                }),

            // NUEVA PROPIEDAD: Solicitudes recibidas en mis anuncios
            'receivedRequests' => $receivedRequests,

            'availableListings' => $availableListings->map(function ($listing) {
                return [
                    'id' => $listing->id,
                    'type' => $listing->type,
                    'price' => $listing->price,
                    'description' => $listing->description,
                    'is_active' => $listing->is_active,
                    'start_date' => $listing->start_date,
                    'end_date' => $listing->end_date,
                    'tractor' => $listing->tractor ? [
                        'id' => $listing->tractor->id,
                        'brand' => $listing->tractor->brand,
                        'model' => $listing->tractor->model,
                        'year' => $listing->tractor->year,
                        'image_url' => $listing->tractor->image_url,
                    ] : null,
                    'seller' => $listing->seller ? [
                        'id' => $listing->seller->id,
                        'first_name' => $listing->seller->first_name,
                        'last_name' => $listing->seller->last_name,
                    ] : null,
                ];
            })
        ]);
    })->name('user.dashboard');

    // Ruta adicional para obtener detalles completos de un anuncio
    Route::get('user/listings/{listing}/details', function (App\Models\Listing $listing) {
        // Verificar que el usuario es propietario del anuncio
        if ($listing->seller_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver este anuncio.');
        }

        // Cargar todas las relaciones necesarias
        $listing->load([
            'tractor',
            'requests' => function ($query) {
                $query->with('requester')->latest();
            }
        ]);

        return response()->json([
            'listing' => [
                'id' => $listing->id,
                'type' => $listing->type,
                'price' => $listing->price,
                'description' => $listing->description,
                'is_active' => $listing->is_active,
                'start_date' => $listing->start_date,
                'end_date' => $listing->end_date,
                'created_at' => $listing->created_at,
                'tractor_id' => $listing->tractor_id,
                'tractor' => $listing->tractor ? [
                    'id' => $listing->tractor->id,
                    'brand' => $listing->tractor->brand,
                    'model' => $listing->tractor->model,
                    'year' => $listing->tractor->year,
                    'horsepower' => $listing->tractor->horsepower,
                    'working_hours' => $listing->tractor->working_hours,
                    'description' => $listing->tractor->description,
                    'image_url' => $listing->tractor->image_url,
                ] : null,
                'requests' => $listing->requests->map(function ($request) {
                    return [
                        'id' => $request->id,
                        'status' => $request->status,
                        'offered_price' => $request->offered_price,
                        'message' => $request->message,
                        'created_at' => $request->created_at,
                        'requester_id' => $request->requester_id,
                        'requester' => $request->requester ? [
                            'id' => $request->requester->id,
                            'first_name' => $request->requester->first_name,
                            'last_name' => $request->requester->last_name,
                        ] : null,
                    ];
                }),
            ]
        ]);
    })->name('user.listings.details');

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
        // Rutas para anuncios de usuario
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

            // Verificar que no hay otro anuncio activo para el mismo tractor
            $existingActiveListing = \App\Models\Listing::where('tractor_id', $validated['tractor_id'])
                ->where('is_active', true)
                ->first();

            if ($existingActiveListing) {
                return back()->withErrors(['tractor_id' => 'Ya existe un anuncio activo para este tractor.']);
            }

            // Agregar el ID del usuario actual
            $validated['seller_id'] = auth()->id();

            // Asegurar que is_active tenga un valor por defecto
            if (!isset($validated['is_active'])) {
                $validated['is_active'] = true;
            }

            try {
                // Crear el anuncio
                $listing = \App\Models\Listing::create($validated);

                return redirect()->route('user.dashboard')
                    ->with('message', 'Anuncio creado correctamente.');
            } catch (\Exception $e) {
                \Log::error('Error al crear anuncio: ' . $e->getMessage());
                return back()->withErrors(['general' => 'Hubo un error al crear el anuncio.']);
            }
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

            // Cargar aperos conectados con información de pivot
            $tractor->load([
                'aperos' => function ($query) {
                    $query->withPivot('attached_at', 'detached_at');
                }
            ]);

            return Inertia::render('User/Tractors/Show', [
                'tractor' => $tractor
            ]);
        })->name('tractors.show');

        // Ruta para crear tractores (actualizada con imágenes)
        Route::post('tractors', function (Illuminate\Http\Request $request) {
            $validated = $request->validate([
                'brand' => ['nullable', 'string', 'max:255'],
                'model' => ['nullable', 'string', 'max:255'],
                'year' => ['nullable', 'integer'],
                'description' => ['nullable', 'string'],
                'horsepower' => ['nullable', 'integer'],
                'working_hours' => ['nullable', 'numeric'],
                'is_available' => ['nullable', 'in:0,1,true,false'], // Permitir valores múltiples
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);

            // Convertir is_available a boolean
            $validated['is_available'] = filter_var($validated['is_available'] ?? true, FILTER_VALIDATE_BOOLEAN);

            // Manejar la subida de imagen
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('tractors', 'public');
                $validated['image'] = $imagePath;
            }

            // Crear el tractor
            $tractor = \App\Models\Tractor::create($validated);

            // Asignar al usuario actual
            $tractor->owners()->attach(auth()->id());

            return redirect()->route('user.dashboard')
                ->with('message', 'Tractor creado correctamente.');
        })->name('tractors.store');

        // Ruta para actualizar tractores (actualizada con imágenes)
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
                'is_available' => ['nullable', 'in:0,1,true,false'], // Permitir valores múltiples
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
                'remove_image' => ['nullable', 'boolean'],
            ]);

            // Convertir is_available a boolean
            $validated['is_available'] = filter_var($validated['is_available'] ?? $tractor->is_available, FILTER_VALIDATE_BOOLEAN);

            // Manejar la imagen
            if ($request->has('remove_image') && $request->remove_image) {
                // Eliminar imagen existente
                if ($tractor->image) {
                    Storage::delete($tractor->image);
                    $validated['image'] = null;
                }
            } elseif ($request->hasFile('image')) {
                // Eliminar imagen anterior si existe
                if ($tractor->image) {
                    Storage::delete($tractor->image);
                }

                // Subir nueva imagen
                $imagePath = $request->file('image')->store('tractors', 'public');
                $validated['image'] = $imagePath;
            }

            // Remover el campo remove_image de los datos validados
            unset($validated['remove_image']);

            $tractor->update($validated);

            return redirect()->route('user.dashboard')
                ->with('message', 'Tractor actualizado correctamente.');
        })->name('tractors.update');

        Route::delete('tractors/{tractor}', function (App\Models\Tractor $tractor) {
            // Verificar que el usuario es propietario del tractor
            if (!$tractor->owners()->where('user_id', auth()->id())->exists()) {
                abort(403, 'No tienes permiso para eliminar este tractor.');
            }

            try {
                // 1. Verificar si el tractor está siendo usado en algún anuncio activo
                $activeListings = $tractor->listings()->where('is_active', true)->count();
                if ($activeListings > 0) {
                    return back()->with('error', 'No puedes eliminar este tractor porque está siendo usado en anuncios activos.');
                }

                // 2. Eliminar primero todas las relaciones del usuario actual
                $tractor->owners()->detach(auth()->id());

                // 3. Si no hay más propietarios, eliminar todas las relaciones y el tractor
                if ($tractor->owners()->count() === 0) {
                    // Eliminar relaciones con aperos (opcional: solo marcar como desconectado)
                    if ($tractor->aperos()->count() > 0) {
                        $aperoIds = $tractor->aperos()->pluck('aperos.id');
                        foreach ($aperoIds as $aperoId) {
                            $tractor->aperos()->updateExistingPivot($aperoId, ['detached_at' => now()]);
                        }
                    }

                    // Eliminar anuncios inactivos relacionados
                    $tractor->listings()->where('is_active', false)->delete();

                    // Finalmente eliminar el tractor
                    $tractor->delete();

                    return redirect()->route('user.tractors.index')
                        ->with('message', 'Tractor eliminado completamente.');
                }

                return redirect()->route('user.tractors.index')
                    ->with('message', 'Tu relación con el tractor ha sido eliminada.');

            } catch (\Exception $e) {
                \Log::error('Error al eliminar tractor: ' . $e->getMessage());
                return back()->with('error', 'Hubo un error al eliminar el tractor. Por favor, inténtalo de nuevo.');
            }
        })->name('tractors.destroy');

        // Rutas para aperos de usuario
        Route::get('aperos', function () {
            $user = auth()->user();

            // Obtener todos los aperos que pertenecen al usuario
            // mediante la relación directa 'user_id' y también los que están conectados a sus tractores
            $aperosByUserId = \App\Models\Apero::where('user_id', $user->id)->get();

            $aperosByTractors = \App\Models\Apero::whereHas('tractors', function ($query) use ($user) {
                $query->whereHas('owners', function ($ownerQuery) use ($user) {
                    $ownerQuery->where('user_id', $user->id);
                });
            })->get();

            // Combinar ambos conjuntos y eliminar duplicados
            $aperos = $aperosByUserId->merge($aperosByTractors)->unique('id');

            // Cargar las relaciones de tractores para los aperos
            $aperoIds = $aperos->pluck('id')->toArray();
            $aperos = \App\Models\Apero::whereIn('id', $aperoIds)
                ->with([
                    'tractors' => function ($query) use ($user) {
                        $query->whereHas('owners', function ($ownerQuery) use ($user) {
                            $ownerQuery->where('user_id', $user->id);
                        });
                    }
                ])->get();

            return Inertia::render('User/Aperos/Index', [
                'aperos' => $aperos
            ]);
        })->name('aperos.index');

        // Ruta para ver un apero específico - versión corregida para permitir ver aperos propios
        Route::get('aperos/{apero}', function (App\Models\Apero $apero) {
            $user = auth()->user();

            // Verificar que el usuario es propietario del apero o tiene acceso a través de sus tractores
            $hasAccess = $apero->user_id === $user->id ||
                $apero->tractors()->whereHas('owners', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->exists();

            if (!$hasAccess) {
                abort(403, 'No tienes permiso para ver este apero.');
            }

            // Cargar las relaciones de tractores
            $apero->load([
                'tractors' => function ($query) use ($user) {
                    $query->whereHas('owners', function ($ownerQuery) use ($user) {
                        $ownerQuery->where('user_id', $user->id);
                    });
                }
            ]);

            return Inertia::render('User/Aperos/Show', [
                'apero' => $apero
            ]);
        })->name('aperos.show');

        // Ruta para crear un nuevo apero (actualizada para que tractor_id sea opcional y añadiendo user_id)
        Route::post('aperos', function (Illuminate\Http\Request $request) {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'nullable|string|max:255',
                'brand' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'year' => 'nullable|integer',
                'description' => 'nullable|string',
                'tractor_id' => 'nullable|exists:tractors,id', // Ahora es nullable
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);

            // Manejar la subida de imagen
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('aperos', 'public');
                $validated['image'] = $imagePath;
            }

            // Crear apero con el ID del usuario actual
            $apero = \App\Models\Apero::create([
                'name' => $validated['name'],
                'type' => $validated['type'] ?? null,
                'brand' => $validated['brand'] ?? null,
                'model' => $validated['model'] ?? null,
                'year' => $validated['year'] ?? null,
                'description' => $validated['description'] ?? null,
                'is_available' => true,
                'image' => $validated['image'] ?? null,
                'user_id' => auth()->id(), // Añadir el ID del usuario autenticado
            ]);

            // Conectar al tractor si se proporcionó un tractor_id
            if (!empty($validated['tractor_id'])) {
                $tractor = \App\Models\Tractor::findOrFail($validated['tractor_id']);

                // Verificar que el tractor pertenece al usuario
                if ($tractor->owners()->where('user_id', auth()->id())->exists()) {
                    $tractor->aperos()->attach($apero->id, ['attached_at' => now()]);
                }
            }

            return redirect()->route('user.dashboard')->with('message', 'Apero creado correctamente.');
        })->name('aperos.store');

        // Ruta para editar un apero existente (actualizada con imágenes)
        Route::put('aperos/{apero}', function (Illuminate\Http\Request $request, \App\Models\Apero $apero) {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'nullable|string|max:255',
                'brand' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'year' => 'nullable|integer',
                'description' => 'nullable|string',
                'tractor_id' => 'nullable|exists:tractors,id', // Cambiado a nullable
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
                'remove_image' => ['nullable', 'boolean'], // Para eliminar imagen existente
            ]);

            // Si se proporcionó un tractor_id, verificar que el usuario tiene permiso sobre el tractor
            if (!empty($validated['tractor_id'])) {
                $tractor = \App\Models\Tractor::findOrFail($validated['tractor_id']);
                if (!$tractor->owners()->where('user_id', auth()->id())->exists()) {
                    abort(403, 'No tienes permiso para usar este tractor.');
                }
            }

            // Manejar la imagen
            if ($request->has('remove_image') && $request->remove_image) {
                // Eliminar imagen existente
                if ($apero->image) {
                    Storage::delete($apero->image);
                    $validated['image'] = null;
                }
            } elseif ($request->hasFile('image')) {
                // Eliminar imagen anterior si existe
                if ($apero->image) {
                    Storage::delete($apero->image);
                }

                // Subir nueva imagen
                $imagePath = $request->file('image')->store('aperos', 'public');
                $validated['image'] = $imagePath;
            }

            // Guardar el tractor_id antes de removerlo de los datos validados
            $tractorId = $validated['tractor_id'] ?? null;

            // Remover campos que no van al modelo
            unset($validated['tractor_id'], $validated['remove_image']);

            // Actualizar campos del apero
            $apero->update($validated);

            // Manejar la relación con el tractor si se proporcionó uno
            if ($tractorId) {
                $tractor = \App\Models\Tractor::findOrFail($tractorId);

                // Verificar si ya existe una conexión con este tractor
                $existingConnection = $apero->tractors()
                    ->where('tractor_id', $tractorId)
                    ->wherePivot('detached_at', null)
                    ->exists();

                if (!$existingConnection) {
                    // Conectar el apero al tractor
                    $tractor->aperos()->attach($apero->id, ['attached_at' => now()]);
                }
            }

            return redirect()->route('user.dashboard')->with('message', 'Apero actualizado correctamente.');
        })->name('aperos.update');

        // API para obtener aperos disponibles para un tractor
        Route::get('aperos/available-for-tractor/{tractor}', function (App\Models\Tractor $tractor) {
            $user = auth()->user();

            // Verificar que el usuario es propietario del tractor
            if (!$tractor->owners()->where('user_id', $user->id)->exists()) {
                abort(403, 'No tienes permiso para ver este tractor.');
            }

            // Obtener aperos que no están conectados a este tractor
            $connectedAperoIds = $tractor->aperos()
                ->wherePivot('detached_at', null)
                ->pluck('aperos.id');

            $availableAperos = \App\Models\Apero::where('is_available', true)
                ->whereNotIn('id', $connectedAperoIds)
                ->get();

            return response()->json([
                'aperos' => $availableAperos
            ]);
        })->name('aperos.available-for-tractor');

        // Ruta para obtener todos los tractores del usuario (API)
        Route::get('api/tractors', function () {
            $user = auth()->user();
            $tractors = $user->tractors()->latest()->get();

            return response()->json([
                'tractors' => $tractors
            ]);
        })->name('api.tractors');

        // Ruta para conectar/desconectar aperos a tractores del usuario
        Route::post('tractors/{tractor}/attach-apero', function (App\Models\Tractor $tractor, Illuminate\Http\Request $request) {
            // Verificar que el usuario es propietario del tractor
            if (!$tractor->owners()->where('user_id', auth()->id())->exists()) {
                abort(403, 'No tienes permiso para modificar este tractor.');
            }

            $validated = $request->validate([
                'apero_id' => ['required', 'exists:aperos,id']
            ]);

            $apero = \App\Models\Apero::find($validated['apero_id']);

            // Verificar si el apero está disponible
            if (!$apero->is_available) {
                return back()->with('error', 'El apero seleccionado no está disponible.');
            }

            // Verificar si el apero ya está conectado a este tractor
            $existingConnection = $tractor->aperos()
                ->where('apero_id', $validated['apero_id'])
                ->wherePivot('detached_at', null)
                ->exists();

            if ($existingConnection) {
                return back()->with('error', 'El apero ya está conectado a este tractor.');
            }

            // Conectar el apero
            $tractor->aperos()->attach($validated['apero_id'], [
                'attached_at' => now(),
                'detached_at' => null,
            ]);

            return back()->with('message', 'Apero conectado al tractor correctamente.');
        })->name('tractors.attach-apero');

        Route::delete('tractors/{tractor}/detach-apero/{apero}', function (App\Models\Tractor $tractor, App\Models\Apero $apero) {
            // Verificar que el usuario es propietario del tractor
            if (!$tractor->owners()->where('user_id', auth()->id())->exists()) {
                abort(403, 'No tienes permiso para modificar este tractor.');
            }

            // Buscar la relación pivot activa
            $pivotRecord = $tractor->aperos()
                ->where('apero_id', $apero->id)
                ->wherePivot('detached_at', null)
                ->first();

            if (!$pivotRecord) {
                return back()->with('error', 'El apero no está conectado a este tractor.');
            }

            // Actualizar la fecha de desconexión en lugar de eliminar la relación
            $tractor->aperos()->updateExistingPivot($apero->id, [
                'detached_at' => now(),
            ]);

            return back()->with('message', 'Apero desconectado del tractor correctamente.');
        })->name('tractors.detach-apero');

        // Rutas para solicitudes
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

        Route::patch('requests/{request}/cancel', [RequestController::class, 'cancel'])->name('requests.cancel');

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

    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/conversations', [ChatController::class, 'getUserConversations'])->name('conversations');
        Route::get('/request/{request}/conversation', [ChatController::class, 'getOrCreateConversation'])->name('request.conversation');
        Route::get('/conversations/{conversation}/messages', [ChatController::class, 'getMessages'])->name('conversation.messages');
        Route::post('/conversations/{conversation}/messages', [ChatController::class, 'sendMessage'])->name('conversation.send');
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