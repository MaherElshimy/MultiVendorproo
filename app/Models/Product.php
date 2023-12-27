<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'discount',
        'color',
        'Size',
        'Weight',
        'quantity',
        'Discount',
        'category_id',
        'Brand',
        'Commission',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
