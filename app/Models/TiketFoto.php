<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TiketFoto extends Model
{
    use HasFactory;

    protected $table = 'tiket_fotos';

    protected $fillable = [
        'tiket_id',
        'path',
    ];

    public function tiket(): BelongsTo
    {
        return $this->belongsTo(Tiket::class, 'tiket_id');
    }

    public function getFotoUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}

