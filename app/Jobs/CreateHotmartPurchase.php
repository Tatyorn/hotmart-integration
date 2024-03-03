<?php

namespace App\Jobs;

use App\Enums\HotmartStatusEnum;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreateHotmartPurchase implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly string $name,
        private readonly string $email,
        private readonly string $address,
        private readonly string $password,
        private readonly string $phone,
        private readonly string $code,
        private readonly int    $product_id,
        private readonly string $purchase_date,
    ) {
        //
    }

    public function handle(): void
    {
        DB::transaction(function () {
            $user = User::query()->firstOrCreate(
                ['email' => $this->email],
                [
                'name' => $this->name,
                'address' => $this->address,
                'password' => $this->password,
                'phone' => $this->phone
                ]
            );

            Subscription::query()->create([
                'code' => $this->code,
                'status' => HotmartStatusEnum::APPROVED,
                'product_id' => $this->product_id,
                'user_id' => $user->id,
                'purchase_date' => $this->purchase_date,
            ]);
        });
    }
}
