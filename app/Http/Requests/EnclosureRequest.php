<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnclosureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $enclosure = $this->route('enclosure');
    
        if (is_string($enclosure) || is_numeric($enclosure)) {
            $enclosure = \App\Models\Enclosure::find($enclosure);
        }
    
        return [
            'name' => [
                'required',
                'string',
                'min:4',
                'max:30',
                Rule::unique('enclosures', 'name')->ignore($enclosure?->id),
            ],
            'is_predator' => $this->isMethod('post') ? 'required|boolean' : 'sometimes|boolean',
            'limit' => [
                'required',
                'integer',
                'min:1',
                'max:15',
                function ($attribute, $value, $fail) use ($enclosure) {
                    if ($enclosure && $enclosure->exists) {
                        $currentAnimalCount = $enclosure->animals()->count();
                        if ($value < $currentAnimalCount) {
                            $fail('The limit cannot be smaller than the number of animals currently in the enclosure (' . $currentAnimalCount . ').');
                        }
                    }
                },
            ],
            'feeding_at' => 'required|date_format:H:i',
        ];
    }
    

    public function messages()
    {
        return [
            'name.required' => 'The enclosure name is required.',
            'name.string' => 'The enclosure name must be a string.',
            'name.min' => 'The enclosure name must be at least 4 characters.',
            'name.max' => 'The enclosure name may not be greater than 30 characters.',
            'name.unique' => 'An enclosure with this name already exists.',
            'limit.required' => 'The limit is required.',
            'limit.integer' => 'The limit must be an integer.',
            'limit.min' => 'The limit must be at least 1.',
            'limit.max' => 'The limit may not be greater than 15.',
            'is_predator.required' => 'The predator status is required.',
            'is_predator.boolean' => 'The predator status must be true or false.',
            'feeding_at.required' => 'The feeding time is required.',
            'feeding_at.date_format' => 'The feeding time must be in the format HH:MM.',
        ];
    }
}
