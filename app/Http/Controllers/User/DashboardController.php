<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Listing as ListingModel;
use App\Models\Request as RequestModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index()
    {
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
    }

    /**
     * Get listing details for a specific listing.
     */
    public function getListingDetails(\App\Models\Listing $listing)
    {
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
    }

    /**
     * Get available listings API.
     */
    public function getAvailableListings()
    {
        $user = auth()->user();
        $listings = ListingModel::with(['tractor', 'seller'])
            ->where('is_active', true)
            ->where('seller_id', '!=', $user->id)
            ->latest()
            ->get();

        return response()->json(['listings' => $listings]);
    }
}