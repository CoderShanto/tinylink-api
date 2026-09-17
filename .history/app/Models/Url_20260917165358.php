<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;

    // Mass assignable fields
    protected $fillable = [
        'user_id',
        'original_url',
        'short_code',
        'click_count',
    ];

    // Relationship: একটি URL একটি User এর belongsTo
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}