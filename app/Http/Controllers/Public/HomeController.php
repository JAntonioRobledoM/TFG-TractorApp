<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Display the public home page.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        // Fetch the latest listings (limit to 6) to display on the home page
        $latestListings = Listing::with(['tractor', 'apero', 'user'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get()
            ->map(function ($listing) {
                // Transform the listing data to include necessary information
                return [
                    'id' => $listing->id,
                    'title' => $listing->title,
                    'type' => $listing->type, // 'sale' or 'rental'
                    'price' => $listing->price,
                    'location' => $listing->location,
                    'tractor' => $listing->tractor ? [
                        'brand' => $listing->tractor->brand,
                        'model' => $listing->tractor->model,
                    ] : null,
                    'apero' => $listing->apero ? [
                        'name' => $listing->apero->name,
                        'type' => $listing->apero->type,
                    ] : null,
                    'user' => $listing->user ? [
                        'name' => $listing->user->first_name . ' ' . $listing->user->last_name,
                    ] : null,
                    // Add a thumbnail image if you have one
                    'thumbnail_url' => $listing->thumbnail_url ?? null,
                ];
            });

        // Asegúrate de que la vista esté en la carpeta correcta: resources/js/Pages/Public/Home.vue
        return Inertia::render('Public/Home', [
            'latestListings' => $latestListings,
        ]);
    }
}