<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders'; // Specify the table name

    protected $fillable = [
        'total_harga',
        'tanggal_order',
        'user_id', // FK to the users table, assuming your application supports login
    ];

    public $timestamps = true; // Enable timestamps for created_at and updated_at

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
