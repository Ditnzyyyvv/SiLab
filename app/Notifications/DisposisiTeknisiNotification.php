<?php

namespace App\Notifications;

use App\Models\Tiket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DisposisiTeknisiNotification extends Notification
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
        return [
            'tiket_id' => $this->tiket->id,
            'nomor_tiket' => $this->tiket->nomor_tiket,
            'judul' => 'Penugasan Perbaikan Baru',
            'pesan' => "Anda ditugaskan menangani tiket {$this->tiket->nomor_tiket} ({$this->tiket->aset->nama}) dengan prioritas {$this->tiket->prioritas}.",
            'url' => route('teknisi.tugas.show', $this->tiket),
            'tipe' => 'disposisi',
        ];
    }
}
