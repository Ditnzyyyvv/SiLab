<?php

namespace App\Notifications;

use App\Models\Tiket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StatusTiketUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Tiket $tiket,
        public string $statusLama,
        public string $catatan
    ) {
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
            'judul' => "Status Tiket: {$this->tiket->status}",
            'pesan' => "Tiket {$this->tiket->nomor_tiket} ({$this->tiket->aset->nama}) kini berstatus '{$this->tiket->status}'. Catatan: {$this->catatan}",
            'url' => route('member.tikets.show', $this->tiket),
            'tipe' => 'status_updated',
        ];
    }
}
