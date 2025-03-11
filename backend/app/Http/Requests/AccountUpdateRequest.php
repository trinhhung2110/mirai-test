<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Traits\FailedValidationTrait;

class AccountUpdateRequest extends FormRequest
{
    use FailedValidationTrait;

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
        $registerID = $this->route('account');
        return [
            'login' => 'required|string|max:20|unique:accounts,login,' . $registerID . ',registerID',
            'password' => 'required|string|max:40',
            'phone' => 'nullable|string|max:20',
        ];
    }
}
