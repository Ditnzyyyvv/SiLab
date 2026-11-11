<?php

namespace App\Models;

use Database\Factories\LabFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lab extends Model
{
    /** @use HasFactory<LabFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'labs';

    protected $fillable = [
        'kode',
        'nama',
        'lokasi',
        'penanggung_jawab_id',
    ];

    /**
     * Guru / Admin penanggung jawab lab.
     */
    public function penanggungJawab(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penanggung_jawab_id');
    }

    /**
     * Seluruh aset yang ditempatkan di lab ini.
     */
    public function asets(): HasMany
    {
        return $this->hasMany(Aset::class, 'lab_id');
    }
}
