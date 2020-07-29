<?php

namespace App\Modules\Taxonomy\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LaravelLocalization;

class SaveTerm extends FormRequest
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
            'id' => 'required_with:edit,1|numeric|exists:terms',
            'vid' => 'required|numeric|exists:vocabularies,id',
            'name' => 'required|string',
            'parent' => 'numeric|min:0'
        ];
    }
}
