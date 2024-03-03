<?php

use App\Enums\HotmartStatusEnum;
use App\Jobs\CancelHotmartPurchase;
use App\Jobs\CreateHotmartPurchase;
use App\Jobs\ExpireHotmartPurchase;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Http;

it('dispatches_stores_a_purchase_job', function () {
    Product::factory()->create();

    $fixture = json_decode(file_get_contents(__DIR__."/fixtures/hotmart_create.json"), true);
    $response = Http::post(env('APP_URL') . '/api/webhook/create', $fixture);

    expect($response->status())->toBe(200);
});

it('stores_a_purchase', function () {
    $product = Product::factory()->create();

    $approvedDate = now();
    $job = app(CreateHotmartPurchase::class, [
        'name' => 'name',
        'email' => 'email@gmail.com',
        'address' => 'address',
        'password' => 'password',
        'phone' => '999999999',
        'code' => '123',
        'status' => HotmartStatusEnum::APPROVED,
        'product_id' => $product->id,
        'purchase_date' => $approvedDate,
    ]);

    $job->handle();

    $this->assertDatabaseHas('subscriptions', [
        'code' => '123',
        'status' => HotmartStatusEnum::APPROVED,
        'purchase_date' => $approvedDate,
    ]);

    $this->assertDatabaseHas('users', [
        'name' => 'name',
        'email' => 'email@gmail.com'
    ]);
});

it('dispatches_cancel_a_purchase_job', function () {
    Product::factory()->create();

    $fixture = json_decode(file_get_contents(__DIR__."/fixtures/hotmart_cancel.json"), true);
    $response = Http::post(env('APP_URL') . '/api/webhook/cancel', $fixture);

    expect($response->status())->toBe(200);
});

it('cancels_a_purchase', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $subscription = Subscription::factory()->state([
        'code' => 123,
        'status' => HotmartStatusEnum::APPROVED,
        'product_id' => $product->id,
        'user_id' => $user->id,
    ])->create();

    $cancelDate = now();
    $job = app(CancelHotmartPurchase::class, [
        'code' => $subscription->code,
        'cancellation_date' => $cancelDate,
    ]);

    $job->handle();

    $this->assertDatabaseHas('subscriptions', [
        'code' => $subscription->code,
        'status' => HotmartStatusEnum::CANCELLED,
        'cancellation_date' => $cancelDate,
    ]);
});


it('dispatches_expire_a_purchase_job', function () {
    Product::factory()->create();

    $fixture = json_decode(file_get_contents(__DIR__."/fixtures/hotmart_create.json"), true);
    $response = Http::post(env('APP_URL') . '/api/webhook/expire', $fixture);

    expect($response->status())->toBe(200);
});

it('expires_a_purchase', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $subscription = Subscription::factory()->state([
        'code' => 123,
        'status' => HotmartStatusEnum::APPROVED,
        'product_id' => $product->id,
        'user_id' => $user->id,
    ])->create();

    $expireDate = now();
    $job = app(ExpireHotmartPurchase::class, [
        'code' => $subscription->code,
        'expiration_date' => $expireDate,
        'product_id' => $product->id,
    ]);

    $job->handle();

    $this->assertDatabaseHas('subscriptions', [
        'code' => $subscription->code,
        'status' => HotmartStatusEnum::EXPIRED,
        'expiration_date' => $expireDate,
        'product_id' => $product->id,
    ]);
});
