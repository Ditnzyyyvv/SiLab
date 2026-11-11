<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogPenanganan extends Model
{
    use HasFactory;

    protected $table = 'log_penanganans';

    protected $fillable = [
        'tiket_id',
        'user_id',
        'status_dari',
        'status_ke',
        'catatan',
        'komponen_dibutuhkan',
        'foto_bukti',
    ];

    public function tiket(): BelongsTo
    {
        return $this->belongsTo(Tiket::class, 'tiket_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getFotoBuktiUrlAttribute(): ?string
    {
        return $this->foto_bukti ? asset('storage/' . $this->foto_bukti) : null;
    }
}
