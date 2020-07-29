<?php

namespace App\Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{

    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        $validator->sometimes('password', 'required|min:8|confirmed', function ($input)
        {
            if (!empty($input->password) || ((empty($input->password) && $this->route()->getName() !== 'user.save'))) {
                return true;
            }

            return false;
        });

        return $validator;

    }
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
            'name' => 'required|max:255',
            'email' =>'unique:users,name,' . $this->id
        ];
    }

}
