<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUrlRequest;
use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class UrlController extends Controller
{
    // List all URLs for the authenticated user with Pagination
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $urls = $request->user()->urls()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'URLs retrieved successfully',
            'data' => $urls
        ]);
    }

    // Create Short URL
    public function store(StoreUrlRequest $request)
    {
        $shortCode = $request->custom_code ?? $this->generateUniqueShortCode();

        $url = $request->user()->urls()->create([
            'original_url' => $request->url,
            'short_code'   => $shortCode,
            'click_count'  => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'URL shortened successfully',
            'data'    => $url
        ], 201);
    }

    // Get URL Details with Policy Authorization
    public function show(Url $url)
    {
        // Policy authorization check
        if (!$this->user()->can('view', $url)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to view this URL'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'URL details retrieved successfully',
            'data' => $url
        ]);
    }

    // Delete URL with Policy Authorization
    public function destroy(Url $url)
    {
        // Policy authorization check
        if (!$this->user()->can('delete', $url)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this URL'
            ], 403);
        }

        $url->delete();

        return response()->json([
            'success' => true,
            'message' => 'URL deleted successfully',
            'data' => null
        ]);
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