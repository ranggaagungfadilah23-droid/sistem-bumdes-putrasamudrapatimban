<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    // Nama tabel di database
    protected $table = 'notifications';

    // Primary key untuk tabel ini biasanya UUID string (default Laravel)
    protected $keyType = 'string';
    public $incrementing = false;

    // Field yang boleh diisi (mass assignment)
    protected $fillable = [
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at'
    ];

    // Kolom 'data' harus dikonversi ke array agar bisa dipanggil $notif->data['title']
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Relasi ke model User (Notifiable)
     */
    public function notifiable()
    {
        return $this->morphTo();
    }
}
