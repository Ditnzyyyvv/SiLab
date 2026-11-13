<?php

namespace App\Notifications;

use App\Models\Tiket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TiketBaruNotification extends Notification
{
    use Queueable;

    public function __construct(public Tiket $tiket)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $pelaporType = $this->tiket->isGuest() ? 'Tamu Lab (QR Scan)' : 'Member ' . $this->tiket->nama_pelapor;

        return [
            'tiket_id' => $this->tiket->id,
            'nomor_tiket' => $this->tiket->nomor_tiket,
            'judul' => 'Pengaduan Tiket Baru',
            'pesan' => "Tiket {$this->tiket->nomor_tiket} diajukan oleh {$pelaporType} untuk aset {$this->tiket->aset->nama}.",
            'url' => route('admin.tikets.show', $this->tiket),
            'tipe' => 'tiket_baru',
        ];
    }
}
