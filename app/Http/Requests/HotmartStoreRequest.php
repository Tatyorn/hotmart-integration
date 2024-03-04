<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class HotmartStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable',
            'email' => 'nullable',
            'address' => 'nullable',
            'password' => 'nullable',
            'phone' => 'nullable',
            'code' => 'nullable',
            'product_id' => 'nullable',
            'purchase_date' => 'nullable',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'name' => $this->input('data.buyer.name'),
            'email' => $this->input('data.buyer.email'),
            'address' => $this->input('data.buyer.address.country'),
            'password' =>  Hash::make('password'),
            'phone' => $this->input('data.buyer.checkout_phone'),
            'code' => $this->input('data.subscription.plan.id'),
            'product_id' => $this->input('data.product.id'),
            'purchase_date' => Carbon::parse($this->input('data.purchase.approved_date')),
        ]);
    }
}
