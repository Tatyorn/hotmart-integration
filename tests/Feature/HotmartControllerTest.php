<?php

use App\Enums\PurchaseStatusEnum;
use App\Enums\SubscriptionStatusEnum;
use App\Jobs\CancelSubscription;
use App\Jobs\CreatePurchase;
use App\Jobs\ExpirePurchase;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Http;

it('dispatches_stores_a_purchase_job', function () {
    Product::factory()->create();

    $fixture = json_decode(file_get_contents(__DIR__."/fixtures/hotmart_create.json"), true);
    $response = Http::post(env('APP_URL') . '/api/hotmart_webhook/create', $fixture);

    expect($response->status())->toBe(200);
});

it('stores_a_purchase', function () {
    $product = Product::factory()->create();
    User::factory()->state([
        'name' => 'name',
        'email' => 'email@gmail.com',
        'address' => 'address',
        'password' => 'password',
        'phone' => '999999999',
    ])->create();

    $approvedDate = now();
    $job = app(CreatePurchase::class, [
        'email' => 'email@gmail.com',
        'code' => '123',
        'product_id' => $product->id,
        'purchase_date' => $approvedDate,
    ]);

    $job->handle();

    $this->assertDatabaseHas('subscriptions', [
        'code' => '123',
        'status' => SubscriptionStatusEnum::ACTIVE,
    ]);

    $this->assertDatabaseHas('purchases', [
        'product_id' => $product->id,
        'status' => PurchaseStatusEnum::APPROVED,
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
    $response = Http::post(env('APP_URL') . '/api/hotmart_webhook/cancel', $fixture);

    expect($response->status())->toBe(200);
});

it('cancels_a_purchase', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $subscription = Subscription::factory()->state([
        'code' => 123,
        'status' => SubscriptionStatusEnum::ACTIVE,
        'user_id' => $user->id,
    ])->create();

    $purchase = Purchase::factory()->state([
        'product_id' => $product->id,
        'subscription_id' => $subscription->id,
    ])->create();

    $cancelDate = now();
    $job = app(CancelSubscription::class, [
        'code' => $subscription->code,
        'cancellation_date' => $cancelDate,
    ]);

    $job->handle();

    $this->assertDatabaseHas('subscriptions', [
        'code' => $subscription->code,
        'status' => SubscriptionStatusEnum::CANCELLED,
        'cancellation_date' => $cancelDate,
    ]);

    $this->assertDatabaseHas('purchases', [
        'id' => $purchase->id,
        'status' => PurchaseStatusEnum::CANCELLED,
    ]);
});


it('dispatches_expire_a_purchase_job', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $subscription = Subscription::factory()->state([
        'code' => 123,
        'status' => SubscriptionStatusEnum::ACTIVE,
        'user_id' => $user->id,
    ])->create();

    Purchase::factory()->state([
        'product_id' => $product->id,
        'subscription_id' => $subscription->id,
    ])->create();

    $fixture = json_decode(file_get_contents(__DIR__."/fixtures/hotmart_expire.json"), true);
    $response = Http::post(env('APP_URL') . '/api/hotmart_webhook/expire', $fixture);

    expect($response->status())->toBe(200);
});

it('expires_a_purchase', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $subscription = Subscription::factory()->state([
        'code' => 123,
        'status' => SubscriptionStatusEnum::ACTIVE,
        'user_id' => $user->id,
    ])->create();

    $purchase = Purchase::factory()->state([
        'product_id' => $product->id,
        'subscription_id' => $subscription->id,
    ])->create();

    $expireDate = now();
    $job = app(ExpirePurchase::class, [
        'code' => $subscription->code,
        'expiration_date' => $expireDate,
        'product_id' => $product->id,
    ]);

    $job->handle();

    $this->assertDatabaseHas('purchases', [
        'id' => $purchase->id,
        'status' => PurchaseStatusEnum::EXPIRED,
        'expiration_date' => $expireDate,
        'product_id' => $product->id,
    ]);
});
