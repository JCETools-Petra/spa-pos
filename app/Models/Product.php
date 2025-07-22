<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['sku', 'name', 'description', 'selling_price'];

    public function branches()
    {
        return $this->belongsToMany(Branch::class)
                    ->withPivot('stock_quantity', 'selling_price')
                    ->withTimestamps();
    }
}