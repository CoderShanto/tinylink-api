<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUrlRequest;
use App\Models\Url;
use Illuminate\Support\Str;

class UrlController extends Controller
{
    // Create Short URL
    public function store(StoreUrlRequest $request)
    {
        // 1. Check if custom code is provided, otherwise generate one
        $shortCode = $request->custom_code ?? $this->generateUniqueShortCode();

        // 2. Create the URL and attach it to the current user
        $url = $request->user()->urls()->create([
            'original_url' => $request->url,
            'short_code'   => $shortCode,
            'click_count'  => 0,
        ]);

        // 3. Return consistent JSON response
        return response()->json([
            'success' => true,
            'message' => 'URL shortened successfully',
            'data'    => $url
        ], 201);
    }

    // Helper method to generate unique short code
    private function generateUniqueShortCode($length = 6)
    {
        do {
            $code = Str::random($length);
        } while (Url::where('short_code', $code)->exists());

        return $code;
    }
}