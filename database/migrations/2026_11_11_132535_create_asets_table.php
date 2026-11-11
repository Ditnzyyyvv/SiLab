<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asets', function (Blueprint $table) {
            $table->id();
            $table->string('kode_aset')->unique();
            $table->string('nama');
            $table->foreignId('kategori_id')->constrained('kategoris')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('lab_id')->constrained('labs')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('merek')->nullable();
            $table->text('spesifikasi')->nullable();
            $table->date('tanggal_perolehan')->nullable();
            $table->enum('kondisi', [
                'Baik',
                'Rusak Ringan',
                'Rusak Berat',
                'Dalam Perbaikan',
                'Tidak Layak Pakai',
            ])->default('Baik');
            $table->string('foto')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asets');
    }
};
