<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class BestSellersRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'isbn' => 'array',
            'isbn.*' => [
                function ($isbn, $value, $fail) {
                    $len = strlen($value);
                    if ($len !== 10 && $len !== 13) {
                        $fail("$isbn must be either 10 or 13 characters long.");
                    }
                },
            ],
            'offset' => [
                function($offset, $value, $fail) {
                    if ($value != 0 && $value % 20 !== 0) {
                        $fail($offset . ' must be zero or a multiple of 20.');
                    }
                },
            ],
        ];
    }

    protected function passedValidation(): void
    {
        $this->replace(['isbn' => $this->collect('isbn')->implode(';')]);
    }
}
