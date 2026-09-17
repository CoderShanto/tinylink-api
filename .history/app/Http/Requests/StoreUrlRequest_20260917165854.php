<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUrlRequest extends FormRequest
{
    // Authorization middleware এ আগেই check করা হবে, তাই true return করছি
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'url' => ['required', 'url', 'max:2048'],
            // Bonus feature এর জন্য custom_code optional রাখলাম
            'custom_code' => ['nullable', 'string', 'alpha_dash', 'max:50', 'unique:urls,short_code'],
        ];
    }
}