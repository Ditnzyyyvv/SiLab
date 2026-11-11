<?php

namespace App\Services;

use App\Models\Aset;
use App\Models\Lab;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AsetService
{
    /**
     * Generate format kode aset unik otomatis: AST-{KODE_LAB}-{URUT}
     * Contoh: AST-RPL-0001
     */
    public function generateKodeAset(int $labId): string
    {
        $lab = Lab::withTrashed()->findOrFail($labId);
        $labKode = strtoupper(trim($lab->kode));

        // Hitung seluruh aset yang pernah dibuat di lab ini (termasuk soft deleted)
        $total = Aset::withTrashed()->where('lab_id', $labId)->count() + 1;
        $urutan = str_pad((string) $total, 4, '0', STR_PAD_LEFT);

        $kodeAset = "AST-{$labKode}-{$urutan}";

        // Antisipasi bila sudah ada yang sama
        while (Aset::withTrashed()->where('kode_aset', $kodeAset)->exists()) {
            $total++;
            $urutan = str_pad((string) $total, 4, '0', STR_PAD_LEFT);
            $kodeAset = "AST-{$labKode}-{$urutan}";
        }

        return $kodeAset;
    }

    /**
     * Simpan data aset baru dengan penanganan foto dan kode otomatis.
     */
    public function createAset(array $data, ?UploadedFile $foto = null): Aset
    {
        if (empty($data['kode_aset'])) {
            $data['kode_aset'] = $this->generateKodeAset((int) $data['lab_id']);
        }

        if ($foto) {
            $path = $foto->store('asets', 'public');
            $data['foto'] = $path;
        }

        return Aset::create($data);
    }

    /**
     * Perbarui data aset dan perbarui foto jika ada foto baru.
     */
    public function updateAset(Aset $aset, array $data, ?UploadedFile $foto = null): Aset
    {
        if ($foto) {
            // Hapus foto lama jika ada
            if ($aset->foto && Storage::disk('public')->exists($aset->foto)) {
                Storage::disk('public')->delete($aset->foto);
            }
            $data['foto'] = $foto->store('asets', 'public');
        }

        $aset->update($data);

        return $aset;
    }

    /**
     * Hapus aset dengan soft delete.
     */
    public function deleteAset(Aset $aset): bool
    {
        return (bool) $aset->delete();
    }

    /**
     * Menghasilkan string SVG QR Code dari URL lapor cepat aset.
     */
    public function generateQrSvg(Aset $aset, int $size = 200): string
    {
        return (string) QrCode::size($size)
            ->format('svg')
            ->margin(1)
            ->generate($aset->lapor_qr_url);
    }
}

