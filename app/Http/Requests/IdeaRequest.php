<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IdeaRequest extends FormRequest
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
        $create = ['title' => 'required|unique:ideas'];
        $update = ['title' => ['required', Rule::unique('ideas')->ignore(request()->id)]];

        $common = [
            'description' => 'required|min:50',
            'category'    => 'required|exists:categories,id',
        ];

        return array_merge($common, request()->has('id') ? $update : $create);
    }
}
