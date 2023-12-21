<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'phone' => 'required'
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation() : void
    {
        $this->merge([
            'phone' => $this->clearPhone($this->input('phone'))
        ]);
    }

    /**
     * @param $phone
     * @return string
     */
    private function clearPhone($phone) : string
    {
        return preg_replace('/[^0-9]+/', '',$phone);

    }
}
