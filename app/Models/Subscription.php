<?php

namespace App\Models;

use App\Enums\PurchaseStatusEnum;
use App\Enums\SubscriptionStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'status',
        'cancellation_date',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'status' => SubscriptionStatusEnum::class,
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

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
}
