<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Product;

class Images extends Model
{
    use HasFactory;
    protected $table = 'images';
    protected $fillable = [
        'id',
        'img','product_id'
    ];
    protected $gaurded = [];

    /**
    * Get the images that owns to the Product
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');  
    }
}
