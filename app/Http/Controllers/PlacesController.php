<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * KAN-14: Google Places (New) proxy. Key stays on the server.
 */
class PlacesController extends Controller
{
    private const AUTOCOMPLETE = 'https://places.googleapis.com/v1/places:autocomplete';
    private const DETAILS = 'https://places.googleapis.com/v1/places/';

    public function autocomplete(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        if (mb_strlen($q) < 3) {
            return response()->json(['suggestions' => []]);
        }

        $key = config('services.google.places_key');
        if (!$key) {
            return response()->json(['suggestions' => []]);
        }

        $response = Http::timeout(8)
            ->withHeaders([
                'X-Goog-Api-Key' => $key,
                'X-Goog-FieldMask' => 'suggestions.placePrediction.placeId,suggestions.placePrediction.text',
            ])
            ->post(self::AUTOCOMPLETE, [
                'input' => mb_substr($q, 0, 200),
                'languageCode' => 'it',
                'includedRegionCodes' => ['IT'],
            ]);

        if (!$response->successful()) {
            Log::warning('places.autocomplete_failed', [
                'status' => $response->status(),
            ]);

            return response()->json(['suggestions' => []]);
        }

        $suggestions = [];
        foreach ($response->json('suggestions') ?? [] as $row) {
            $prediction = $row['placePrediction'] ?? null;
            if (!$prediction || empty($prediction['placeId'])) {
                continue;
            }
            $suggestions[] = [
                'place_id' => $prediction['placeId'],
                'label' => $prediction['text']['text'] ?? '',
            ];
        }

        return response()->json(['suggestions' => $suggestions]);
    }

    public function details(Request $request)
    {
        $placeId = trim((string) $request->query('place_id', ''));
        if ($placeId === '') {
            return response()->json(['ok' => false], 422);
        }

        $key = config('services.google.places_key');
        if (!$key) {
            return response()->json(['ok' => false], 503);
        }

        $response = Http::timeout(8)
            ->withHeaders([
                'X-Goog-Api-Key' => $key,
                'X-Goog-FieldMask' => 'addressComponents,formattedAddress',
            ])
            ->get(self::DETAILS . rawurlencode($placeId));

        if (!$response->successful()) {
            Log::warning('places.details_failed', [
                'status' => $response->status(),
            ]);

            return response()->json(['ok' => false], 502);
        }

        return response()->json([
            'ok' => true,
            'formatted' => $response->json('formattedAddress'),
            'parts' => $this->parseComponents($response->json('addressComponents') ?? []),
        ]);
    }

    private function parseComponents(array $components): array
    {
        $get = function (array $types, bool $short = false) use ($components) {
            foreach ($components as $component) {
                $have = $component['types'] ?? [];
                foreach ($types as $type) {
                    if (in_array($type, $have, true)) {
                        return $short
                            ? ($component['shortText'] ?? $component['longText'] ?? '')
                            : ($component['longText'] ?? $component['shortText'] ?? '');
                    }
                }
            }

            return '';
        };

        $route = $get(['route']);
        $number = $get(['street_number']);
        $address = trim($route . ($number !== '' ? ' ' . $number : ''));
        $city = $get(['locality', 'administrative_area_level_3', 'postal_town']);
        $postal = $get(['postal_code']);
        $province = strtoupper($get(['administrative_area_level_2'], true));
        if (!preg_match('/^[A-Z]{2}$/', $province)) {
            $province = '';
        }

        return [
            'address' => $address,
            'street_number' => $number,
            'city' => $city,
            'postal_code' => $postal,
            'province' => $province,
        ];
    }
}
