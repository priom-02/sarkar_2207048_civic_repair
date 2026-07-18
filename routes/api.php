<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

Route::get('/geocode/reverse', function (Request $request) {
    $lat = $request->query('lat');
    $lon = $request->query('lon');

    if (!$lat || !$lon) {
        return response()->json(['error' => 'Latitude and longitude are required.'], 400);
    }

    try {
        $response = Http::withHeaders([
            'User-Agent' => 'CivicReporting/1.0 (contact@civicreporting.bd)'
        ])->get('https://nominatim.openstreetmap.org/reverse', [
            'lat' => $lat,
            'lon' => $lon,
            'format' => 'json'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return response()->json([
                'success' => true,
                'address' => $data['display_name'] ?? 'Address not found',
                'raw' => $data
            ]);
        }

        return response()->json(['error' => 'Failed to fetch address from geocoding service.'], 502);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Geocoding service error: ' . $e->getMessage()], 500);
    }
});
