<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions; 

class Transaction extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'invoice_number',
        'branch_id',
        'package_id',
        'therapist_user_id',
        'cashier_user_id',
        'customer_name',
        'total_amount',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Kita hanya log event 'created' karena transaksi jarang diubah/dihapus
            ->logOnly(['package_id', 'total_amount', 'customer_name'])
            ->setDescriptionForEvent(function(string $eventName) {
                if ($eventName === 'created') {
                    return "Transaksi baru telah dibuat";
                }
                return "Transaksi ini telah {$eventName}";
            })
            ->useLogName('Transaction');
    }
    protected $casts = [
        'total_amount' => 'integer',
    ];

    /**
     * Relasi: Transaksi ini milik satu cabang.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Relasi: Transaksi ini untuk satu paket.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Relasi: Transaksi ini dikerjakan oleh satu terapis (User).
     */
    public function therapist()
    {
        return $this->belongsTo(User::class, 'therapist_user_id');
    }

    /**
     * Relasi: Transaksi ini diinput oleh satu kasir (User).
     */
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_user_id');
    }
    // ... di dalam file Transaction.php
	public function products()
	{
	    return $this->belongsToMany(Product::class)
	                ->withPivot('quantity', 'price_at_sale');
	}
}