<?php

namespace App\Notifications;

use App\Models\Tiket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TiketReopenedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Tiket $tiket,
        public string $alasan
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $url = $notifiable->role === 'teknisi'
            ? route('teknisi.tugas.show', $this->tiket)
            : route('admin.tikets.show', $this->tiket);

        return [
            'tiket_id' => $this->tiket->id,
            'nomor_tiket' => $this->tiket->nomor_tiket,
            'judul' => 'Tiket Dibuka Kembali oleh Pelapor',
            'pesan' => "Tiket {$this->tiket->nomor_tiket} ({$this->tiket->aset->nama}) dibuka kembali oleh pelapor. Alasan: {$this->alasan}",
            'url' => $url,
            'tipe' => 'tiket_reopened',
        ];
    }
}

