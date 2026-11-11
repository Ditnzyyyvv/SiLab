<?php

namespace App\Models;

use Database\Factories\AsetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aset extends Model
{
    /** @use HasFactory<AsetFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'asets';

    protected $fillable = [
        'kode_aset',
        'nama',
        'kategori_id',
        'lab_id',
        'merek',
        'spesifikasi',
        'tanggal_perolehan',
        'kondisi',
        'foto',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_perolehan' => 'date',
        ];
    }

    /**
     * Lab tempat aset berada.
     */
    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class, 'lab_id');
    }

    /**
     * Kategori aset.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * URL foto aset di storage.
     */
    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? asset('storage/' . $this->foto) : null;
    }

    /**
     * URL tujuan scan QR untuk pelaporan cepat aset.
     */
    public function getLaporQrUrlAttribute(): string
    {
        return url('/lapor/' . $this->kode_aset);
    }
}
