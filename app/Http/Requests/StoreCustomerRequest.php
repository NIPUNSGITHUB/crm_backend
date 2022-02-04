<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'title' => 'required',
            'first_name' => 'required|max:20|unique:customers',
            'last_name' => 'required|max:20',
            'email' => 'required|email|unique:customers',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10' 
        ];
    }
}
