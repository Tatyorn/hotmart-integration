<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class HotmartExpireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'nullable',
            'expiration_date' => 'nullable',
            'product_id' => 'nullable',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'code' => $this->input('data.subscription.plan.id'),
            'expiration_date' => Carbon::parse($this->input('creation_date')),
            'product_id' => $this->input('data.product.id'),
        ]);
    }
}
