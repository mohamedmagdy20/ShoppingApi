<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'name_en','name_ar','description_en','description_ar','price_in',
        'price_out','stock','categories_id','suppliers_id'
    ];
    protected $gaurded = [];

    /**
    * Get the category that owns the Product
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function category()
    {
        return $this->belongsTo(Category::class,'categories_id');  
    }

    /**
    * Get the suppliers that owns the Product
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'suppliers_id');  
    }

    public function images()
    {
        return $this->hasMany(Images::class);  
    }

    


}
