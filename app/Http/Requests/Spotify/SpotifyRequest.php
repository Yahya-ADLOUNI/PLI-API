<?php

namespace App\Http\Requests\Spotify;

use Illuminate\Foundation\Http\FormRequest;

class SpotifyRequest extends FormRequest
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
        return [
            'input' => ['nullable', 'string'],
            'offset' => ['nullable', 'int'],
        ];
    }
}
