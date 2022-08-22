<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourse extends FormRequest
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
        // TODO: validation of strings
        return [
            'title' => 'bail|required|min:3|max:100',
            'description' => 'bail|required|min:3',
            'rating' => 'min:0|max:5',
            'access_right_id' => 'min:0|max:10'
        ];
    }
}
