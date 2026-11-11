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
        Schema::create('tikets', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_tiket')->unique();
            $table->foreignId('aset_id')->constrained('asets')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('pelapor_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->string('nama_pelapor');
            $table->string('kontak_pelapor')->nullable();
            $table->string('judul');
            $table->text('deskripsi');
            $table->enum('prioritas', ['Rendah', 'Sedang', 'Tinggi'])->nullable();
            $table->enum('status', [
                'Pending',
                'Diproses',
                'Menunggu Komponen/Sparepart',
                'Selesai',
                'Rusak Total',
                'Ditolak',
            ])->default('Pending');
            $table->foreignId('teknisi_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->text('alasan_penolakan')->nullable();
            $table->timestamp('tanggal_disposisi')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamp('dikonfirmasi_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tikets');
    }
};
