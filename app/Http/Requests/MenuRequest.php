<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
            'p_id' => 'nullable|integer',
            'title' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'active_routes' => 'nullable|string', // pipe-separated
            'status' => 'nullable|boolean',
            'invisible' => 'nullable|boolean',
            'sortable' => 'nullable|boolean',
            'svg' => 'nullable|file|mimetypes:image/svg+xml,image/png,image/jpeg,image/jpg|max:5120',
        ];
    }
}
