<?php

namespace App\Jobs;

use App\Enums\HotmartStatusEnum;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExpireHotmartPurchase implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly string $code,
        private readonly string $expiration_date,
        private readonly string $product_id,
    ) {
        //
    }

    public function handle(): void
    {
        Subscription::query()
            ->where('code', $this->code)
            ->where('product_id', $this->product_id)
            ->update([
                'status' => HotmartStatusEnum::EXPIRED,
                'expiration_date' => $this->expiration_date,
            ]);
    }
}
