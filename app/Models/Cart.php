<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table ='carts';
    protected $fillable = [
        'id',
        'client_id',
        'product_id',
        'stock'
    ];

     /**
     * Get the clinet that make the order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    
    /**
     * Get the admin that manage the order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function totalprice()
    {
        return "asdasd";
    //     return $this->product->sum(function($product){
    //         return $this->stock * $product->price_out;
    // });
    }


}

