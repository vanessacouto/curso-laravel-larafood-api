<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateUser extends FormRequest
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
        $id = $this->segment(3);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', "unique:users,email,{$id},id"],
            'password' => ['required', 'string', 'min:6', 'max:16'],
        ];

        // as regras mudam para quando estamos atualizando/criando o registro
        if ($this->method() == 'PUT') { // quando está atualizando usuário o campo password não é obrigatório
            $rules ['password'] = ['nullable', 'string', 'min:6', 'max:16'];
        }

        return $rules;
    }
}
