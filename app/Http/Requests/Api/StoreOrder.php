<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrder extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token_company' => [
                'required',
                'exists:tenants,uuid'
            ],
            'table' => [
                'nullable',
                'exists:tables,uuid'
            ], // esse valor deve existir na coluna 'uuid' da tabela 'tables'
            'comment' => [
                'nullable',
                'max:1000'
            ],
            'products' => ['required'],
            // valida o que está sendo enviado em 'products'
            'products.*.identify' => ['required', 'exists:products,uuid'],
            'products.*.qty' => ['required', 'integer']

        ];
    }
}
