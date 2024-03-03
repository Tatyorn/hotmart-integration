<?php

namespace App\Http\Controllers;

use App\Http\Requests\HotmartCancelRequest;
use App\Http\Requests\HotmartExpireRequest;
use App\Http\Requests\HotmartStoreRequest;
use App\Jobs\CancelHotmartPurchase;
use App\Jobs\CreateHotmartPurchase;
use App\Jobs\ExpireHotmartPurchase;

class SubscriptionController extends Controller
{
    public function store(HotmartStoreRequest $request)
    {
        dispatch(app(CreateHotmartPurchase::class, $request->validated()));

        return response('', 200);
    }

    public function cancel(HotmartCancelRequest $request)
    {
        dispatch(app(CancelHotmartPurchase::class, $request->validated()));

        return response('', 200);
    }

    public function expire(HotmartExpireRequest $request)
    {
        dispatch(app(ExpireHotmartPurchase::class, $request->validated()));

        return response('', 200);
    }
}
