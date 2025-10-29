<?php
    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;

    class SubscribeRequest extends FormRequest
    {
        public function authorize(): bool
        {
            // Only authenticated users can subscribe
            return auth()->check();
        }

        public function rules(): array
        {
            return [
                'plan_id' => 'required|exists:subscription_plans,id',
            ];
        }
    }
