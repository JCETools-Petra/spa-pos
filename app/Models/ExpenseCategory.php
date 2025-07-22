<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions; 

class ExpenseCategory extends Model
{
    use HasFactory, LogsActivity;
    protected $fillable = ['name', 'description'];

    /**
     * Relasi: Satu kategori bisa memiliki banyak pengeluaran.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'description']) // Catat perubahan pada kolom ini
            ->logOnlyDirty()                  // Hanya catat jika ada yang benar-benar berubah
            ->setDescriptionForEvent(fn(string $eventName) => "Kategori Pengeluaran ini telah {$eventName}")
            ->useLogName('Expense Category');
    }
}