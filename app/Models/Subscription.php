<?php

namespace App\Models;

use App\Enums\HotmartStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'status',
        'purchase_date',
        'expiration_date',
        'cancellation_date',
        'product_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'status' => HotmartStatusEnum::class,
        'purchase_date' => 'datetime',
        'expiration_date' => 'datetime',
        'cancellation_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function product(): HasOne
    {
        return $this->hasOne(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
