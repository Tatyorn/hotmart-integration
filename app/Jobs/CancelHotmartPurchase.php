<?php

namespace App\Jobs;

use App\Enums\HotmartStatusEnum;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CancelHotmartPurchase implements ShouldQueue
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
        Subscription::query()
            ->where('code', $this->code)
            ->update([
                'status' => HotmartStatusEnum::CANCELLED,
                'cancellation_date' => $this->cancellation_date,
            ]);
    }
}
