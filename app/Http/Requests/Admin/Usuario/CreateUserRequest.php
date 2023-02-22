<?php

namespace App\Http\Requests\Admin\Usuario;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function messages(){
        return [
            'nombre.required'   => 'El nombre es requerido!',
            'email.required'   => 'El email es requerido!',
            'password.required'   => 'El password es requerido!',
            'password.min'   => 'El password debe contener al mínimo 8 caracteres!',
            'email.email'   => 'El email es inválido!',
            'email.unique'   => 'El email ingresado ya existe!',
            'nombre.string'   => 'El nombre debe ser un texto!',
        ];
    }
}
