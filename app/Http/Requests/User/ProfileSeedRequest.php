<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileSeedRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = ['data' => ['required', 'array']];
        foreach ($this->request->all('data') as $key => $value) {
            $rules['data.' . $key . '.foreign_id'] = ['required', 'string'];
            $rules['data.' . $key . '.name'] = ['required', 'string'];
            $rules['data.' . $key . '.source_id'] = ['required', Rule::exists('sources', 'id')];
        }
        return $rules;
    }
}
