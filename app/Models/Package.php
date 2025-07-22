<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;  

class Package extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'branch_id',
        'name',
        'description',
        'price',
        'duration_minutes',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi: Sebuah paket dimiliki oleh satu cabang.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'price', 'is_active']) // Hanya catat perubahan pada kolom ini
            ->logOnlyDirty() // Hanya catat jika ada perubahan
            ->setDescriptionForEvent(fn(string $eventName) => "Paket ini telah {$eventName}")
            ->useLogName('Package');
    }
}