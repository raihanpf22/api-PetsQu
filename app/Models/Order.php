<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = "orders";
    protected $primaryKey = "order_id";
    
    protected $fillable = [
        'order_user_id',
        'order_product_id',
        'order_name_product',
        'order_img',
        'quantity',
        'ammount',
        "order_address",
        "order_email",
        "order_status"
    ];
}
