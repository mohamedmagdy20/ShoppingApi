<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    protected $tabel = 'orders';
    protected $fillable = [
        'client_id','admin_id','status',
        'created_at','updated_at'
    ];


    /**
     * Get the clinet that make the order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinet()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    
    /**
     * Get the admin that manage the order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(orderDetails::class);  
    }


}
