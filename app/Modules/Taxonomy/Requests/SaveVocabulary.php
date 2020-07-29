<?php

namespace App\Modules\Taxonomy\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LaravelLocalization;

class SaveVocabulary extends FormRequest
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
            'id' => 'required_with:edit,1|numeric|exists:vocabularies',
            'name' => 'required|string',
            'slug' => [
                'required',
                'alpha_dash',
                Rule::unique('vocabularies', 'slug')->ignore($this->input('slug'), 'slug')
            ],
            'locale' => [
                'alpha',
            ],
            'weight' => 'numeric|min:0'
        ];
    }
}
