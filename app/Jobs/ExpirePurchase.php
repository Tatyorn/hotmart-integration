<?php

namespace App\Jobs;

use App\Enums\PurchaseStatusEnum;
use App\Models\Purchase;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExpirePurchase implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private Model|Subscription $subscription;

    public function __construct(
        private readonly string $code,
        private readonly string $expiration_date,
        private readonly string $product_id,
    ) {
        $this->subscription = Subscription::query()->firstWhere('code', $this->code);
    }

    public function handle(): void
    {
        Purchase::query()
            ->where('subscription_id', $this->subscription->id)
            ->where('product_id', $this->product_id)
            ->update([
                'status' => PurchaseStatusEnum::EXPIRED,
                'expiration_date' => $this->expiration_date,
            ]);
    }
}
