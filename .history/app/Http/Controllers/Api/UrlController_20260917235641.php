<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUrlRequest;
use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UrlController extends Controller
{
    // 1. List all URLs for the authenticated user with Pagination (Phase 5)
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

    // 2. Create Short URL (Phase 4)
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

    // 3. Get URL Details with Policy Authorization (Phase 6 & 7)
    public function show(Url $url)
    {
        // Policy check: User কি এই URL টির মালিক?
        $this->authorize('view', $url);

        return response()->json([
            'success' => true,
            'message' => 'URL details retrieved successfully',
            'data' => $url
        ]);
    }

    // 4. Delete URL with Policy Authorization (Phase 8)
    public function destroy(Url $url)
    {
        // Policy check: User কি এই URL টি ডিলিট করার অধিকার রাখে?
        $this->authorize('delete', $url);

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