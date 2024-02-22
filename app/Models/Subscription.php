<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'purchase_date',
        'expiration_date',
        'cancellation_date',
        'id_product',
        'created_at',
        'updated_at'
    ];
}
