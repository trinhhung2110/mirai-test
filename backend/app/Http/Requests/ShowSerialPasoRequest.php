<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Traits\FailedValidationTrait;
class ShowSerialPasoRequest extends FormRequest
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
        return [
            'file' => 'required|string|max:128',
            'app_env' => 'required|integer|in:0,1,2',
            'contract_server' => 'required|integer|in:0,1',
        ];
    }
}
