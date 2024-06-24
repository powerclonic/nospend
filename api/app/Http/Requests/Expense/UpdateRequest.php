<?php

namespace App\Http\Requests\Expense;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['bail', 'required', 'exists:expenses,id'],

            'name' => ['string', 'min:2', 'max:64'],
            'value' => ['integer'],

            'due_date' => ['date'],

            'category' => ['min:2', 'string:32'],
            'payment_method' => ['min:2', 'string:32'],
            'payment_source' => ['min:2', 'string:32'],

            'recurrent' => ['boolean'],
            'auto_pay' => ['boolean'],
        ];
    }
}
