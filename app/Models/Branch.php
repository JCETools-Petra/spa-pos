<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Branch extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'address', 'phone_number', 'profit_sharing_percentage'];

    /**
     * Relasi: Satu cabang memiliki banyak Users.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relasi: Satu cabang memiliki banyak Packages.
     */
    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    /**
     * Relasi: Satu cabang memiliki banyak Transactions.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'address', 'phone_number'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Cabang ini telah {$eventName}")
            ->useLogName('Branch');
    }
    public function products()
    {
        return $this->belongsToMany(Product::class)
                    ->withPivot('stock_quantity', 'selling_price')
                    ->withTimestamps();
    }
}