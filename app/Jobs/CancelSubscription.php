<?php

namespace App\Jobs;

use App\Enums\PurchaseStatusEnum;
use App\Enums\SubscriptionStatusEnum;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CancelSubscription implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly string $code,
        private readonly string $cancellation_date,
    ) {
        //
    }

    public function handle(): void
    {
        DB::transaction(function () {
            $subscription = Subscription::query()->firstWhere('code', $this->code);
            $subscription->update([
                'status' => SubscriptionStatusEnum::CANCELLED,
                'cancellation_date' => $this->cancellation_date,
            ]);

            $subscription->purchases()->update(['status' => PurchaseStatusEnum::CANCELLED]);
        });
    }
}
