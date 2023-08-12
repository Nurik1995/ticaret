<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'carts';
    // protected $fillable = [];
    protected $guarded = [];

    public function products()
    {
        return $this->belongsTo(Product::class, 'prod_id', 'id');
    }
}
