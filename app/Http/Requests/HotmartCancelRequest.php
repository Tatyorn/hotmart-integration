<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class HotmartCancelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'nullable',
            'cancellation_date' => 'nullable',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => $this->input('data.subscription.plan.id'),
            'cancellation_date' => Carbon::parse($this->input('data.cancellation_date')),
        ]);
    }
}
