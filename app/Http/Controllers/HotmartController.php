<?php

namespace App\Http\Controllers;

use App\Http\Requests\HotmartCancelRequest;
use App\Http\Requests\HotmartExpireRequest;
use App\Http\Requests\HotmartStoreRequest;
use App\Jobs\CancelSubscription;
use App\Jobs\CreatePurchase;
use App\Jobs\CreateUser;
use App\Jobs\ExpirePurchase;
use App\Models\User;
use Illuminate\Support\Facades\Bus;

class HotmartController extends Controller
{
    public function store(HotmartStoreRequest $request)
    {
        Bus::chain([
            app(CreateUser::class, $request->validated()),
            app(CreatePurchase::class, $request->validated())
        ]);

        return response('', 200);
    }

    public function cancel(HotmartCancelRequest $request)
    {
        dispatch(app(CancelSubscription::class, $request->validated()));

        return response('', 200);
    }

    public function expire(HotmartExpireRequest $request)
    {
        dispatch(app(ExpirePurchase::class, $request->validated()));

        return response('', 200);
    }
}
