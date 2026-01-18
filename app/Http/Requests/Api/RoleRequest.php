<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255|unique:roles,name',
            'guard_name' => 'required|string|max:255',
            'group' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ];

        // If updating, ignore current role in unique check
        if ($this->method() === 'PUT' || $this->method() === 'PATCH') {
            $role = $this->route('role');
            $rules['name'] = 'required|string|max:255|unique:roles,name,' . $role->id;
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Role name is required',
            'name.unique' => 'This role name already exists',
            'name.max' => 'Role name must not exceed 255 characters',
            'guard_name.required' => 'Guard name is required',
            'group.max' => 'Group name must not exceed 255 characters',
            'description.max' => 'Description must not exceed 1000 characters',
        ];
    }
}