<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $request = $request->all();

        $user = new User();
        $user->name = $request['data']['buyer']['name'];
        $user->email = $request['data']['buyer']['email'];
        $user->address = $request['data']['buyer']['address']['country'];
        $user->password = Hash::make('password');
        $user->phone = $request['data']['buyer']['checkout_phone'];
        $user->save();

        $subscription = new Subscription();
        $subscription->id = $request['data']['subscription']['plan']['id'];
        $subscription->status = 'approved';
        $subscription->product_id = $request['data']['product']['id'];
        $subscription->user_id = $user->id;
        $subscription->purchase_date = Carbon::parse($request['data']['purchase']['approved_date']);
        $subscription->save();

        return response('', 200);
    }

    public function cancel(Request $request)
    {
        $request = $request->all();
        Subscription::query()
            ->where('id', $request['data']['subscription']['id'])
            ->update([
                'status' => 'canceled',
                'cancellation_date' => Carbon::parse($request['data']['cancellation_date']),
            ]);

        return response('', 200);
    }

    public function expire(Request $request)
    {
        $request = $request->all();
        Subscription::query()
            ->where('id', $request['data']['subscription']['plan']['id'])
            ->update([
                'status' => 'expired',
                'expiration_date' => Carbon::parse($request['creation_date']),
            ]);

        return response('', 200);
    }
}
