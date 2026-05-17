<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'rating',
        'comment',
        'visible',
        'rejected',
        'rejected_at',
    ];

    protected function casts(): array
    {
        return [
            'rating'      => 'integer',
            'visible'     => 'boolean',
            'rejected'    => 'boolean',
            'rejected_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
