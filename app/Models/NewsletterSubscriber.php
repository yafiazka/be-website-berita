<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'is_verified',
        'token',
        'unsubscribed_at',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'unsubscribed_at' => 'datetime',
    ];
}
