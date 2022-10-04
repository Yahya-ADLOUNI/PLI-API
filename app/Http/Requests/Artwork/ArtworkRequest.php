<?php

namespace App\Http\Requests\Artwork;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArtworkRequest extends FormRequest
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
            'name' => ['required', 'string', Rule::unique('artworks', 'name')],
            "foreign_id" => ['required', 'string'],
            "source_id" => ['required', 'integer', Rule::exists('sources', 'id')],
        ];
    }
}
