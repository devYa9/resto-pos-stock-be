<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenuRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'menu_name' => [
                'required',
                'unique:menus',
            ],
            'slug' => [
                'sometimes',
                'string',
                'unique:menus',
            ],
            'image_url' => [
                'sometimes',
                'image',
            ],
        ];
        if ($this->method() == Request::METHOD_PUT) {
            $rules['menu_name'][1] = Rule::unique('menus')->ignore($this->route()->parameter('menu'));
        }
        return $rules;
    }
}
