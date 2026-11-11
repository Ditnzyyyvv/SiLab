<?php

namespace App\Models;

use Database\Factories\TiketFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tiket extends Model
{
    /** @use HasFactory<TiketFactory> */
    use HasFactory;

    protected $table = 'tikets';

    protected $fillable = [
        'nomor_tiket',
        'aset_id',
        'pelapor_id',
        'nama_pelapor',
        'kontak_pelapor',
        'judul',
        'deskripsi',
        'prioritas',
        'status',
        'teknisi_id',
        'alasan_penolakan',
        'tanggal_disposisi',
        'tanggal_selesai',
        'dikonfirmasi_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_disposisi' => 'datetime',
            'tanggal_selesai' => 'datetime',
            'dikonfirmasi_at' => 'datetime',
        ];
    }

    /**
     * Gunakan nomor_tiket sebagai route key default untuk URL ramah & aman.
     */
    public function getRouteKeyName(): string
    {
        return 'nomor_tiket';
    }

    /**
     * Mendukung route model binding baik via nomor_tiket maupun ID numerik.
     */
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        return $this->where('nomor_tiket', $value)
            ->orWhere('id', $value)
            ->firstOrFail();
    }

    /**
     * Aset yang dilaporkan rusak.
     */
    public function aset(): BelongsTo
    {
        return $this->belongsTo(Aset::class, 'aset_id');
    }

    /**
     * Pengguna terdaftar yang membuat tiket (nullable jika pelapor tamu via QR).
     */
    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    /**
     * Teknisi yang ditugaskan menangani tiket ini.
     */
    public function teknisi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }

    /**
     * Foto-foto bukti kerusakan fisik yang dilampirkan.
     */
    public function fotos(): HasMany
    {
        return $this->hasMany(TiketFoto::class, 'tiket_id');
    }

    /**
     * Riwayat log penanganan dari awal hingga akhir.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(LogPenanganan::class, 'tiket_id')->latest();
    }

    /**
     * Cek apakah pelapor adalah tamu tanpa login.
     */
    public function isGuest(): bool
    {
        return is_null($this->pelapor_id);
    }
}
