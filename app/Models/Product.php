<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'description',
        'origprice',
        'price',
        'stock',
        'discount',
        'product_pic',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function getDiscountedPrice()
    {
        $discountedPrice = $this->price * (1 - $this->discount / 100);
        return round($discountedPrice, 2);
    }

    public function isInStock()
    {
        return $this->stock > 0;
    }
}
