<?php

namespace App\Jobs;

use App\Enums\PurchaseStatusEnum;
use App\Enums\SubscriptionStatusEnum;
use App\Models\Purchase;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreatePurchase implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly string $email,
        private readonly string $code,
        private readonly int    $product_id,
        private readonly string $purchase_date,
    ) {
        //
    }

    public function handle(): void
    {
        DB::transaction(function () {
            $subscription = Subscription::query()->firstOrCreate(
                ['code' => $this->code],
                [
                    'status' => SubscriptionStatusEnum::ACTIVE,
                    'user_id' => User::query()->firstWhere('email', $this->email)->id,
                ]
            );

            Purchase::query()->create([
                'product_id' => $this->product_id,
                'subscription_id' => $subscription->id,
                'status' => PurchaseStatusEnum::APPROVED,
                'purchase_date' => $this->purchase_date,
            ]);
        });
    }
}
