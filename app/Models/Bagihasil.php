<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class BagiHasil extends Model
{
    protected $table = 'bagihasils';

  protected $fillable = [
    'mitra_id',
    'total_omzet',
    'persen_bumdes',
    'persen_mitra',
    'nominal_bumdes',
    'nominal_mitra',
    'status',
    'tanggal'
];



    public function mitra()
    {
        return $this->belongsTo(User::class, 'mitra_id');
    }

     use LogsActivity;
      public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'total_omzet', 'nominal_bumdes'])
            ->setDescriptionForEvent(fn(string $eventName) => "Bagi hasil {$eventName}")
            ->useLogName('bagihasil');
    }
}
