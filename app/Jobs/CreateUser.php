<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly string $name,
        private readonly string $email,
        private readonly string $address,
        private readonly string $password,
        private readonly string $phone,
    )
    {
        //
    }

    public function handle(): void
    {
        User::query()->firstOrCreate(
            ['email' => $this->email],
            [
                'name' => $this->name,
                'address' => $this->address,
                'password' => $this->password,
                'phone' => $this->phone
            ]
        );
    }
}
