<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tree extends Model
{
    //fillable attributes
    protected $fillable = [
        'name',
    ];

    protected $casts = [
    'next_care_at' => 'datetime',
    ];//Lo lee como fecha y no como texto

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
