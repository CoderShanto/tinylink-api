<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function redirect($shortCode)
    {
        // 1. Find the URL by short code
        $url = Url::where('short_code', $shortCode)->first();

        // 2. If not found, return 404
        if (! $url) {
            abort(404, 'Short URL not found');
        }

        // 3. Increase the click count
        $url->increment('click_count');

        // 4. Redirect to the original URL
        return redirect()->to($url->original_url);
    }
}