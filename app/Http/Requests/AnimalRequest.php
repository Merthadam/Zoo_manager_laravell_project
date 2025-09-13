<?php

namespace App\Http\Requests;


use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Support\SpeciesMap;
use App\Models\Enclosure;
use App\Models\Animal;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\EnclosureController;

class AnimalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
        return [
            'name' => 'required|string|min:5|max:30',
            'species' => 'required|string|in:' . implode(',', SpeciesMap::species()),
            'born_at' => 'required|date_format:Y-m-d',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'enclosure_id' => 'required|exists:enclosures,id',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $species = trim($this->input('species'));
            $enclosure = Enclosure::with('animals')->find($this->input('enclosure_id'));
            $expectedPredator = SpeciesMap::isPredator($species);

            if ($expectedPredator === null) {
                $validator->errors()->add('species', 'Invalid species selected.');
                return;
            }

            if (!$enclosure) {
                return;
            }

            if ($enclosure->is_predator !== $expectedPredator) {
                $validator->errors()->add(
                    'enclosure_id',
                    'This enclosure is for ' . ($enclosure->is_predator ? 'predators' : 'herbivores') . '.'
                );
            }

            $currentAnimalId = $this->route('animal')?->id;
            $animalCount = $enclosure->animals()->where('id', '!=', $currentAnimalId)->count();

            if ($animalCount >= $enclosure->limit) {
                $validator->errors()->add(
                    'enclosure_id',
                    'This enclosure has reached its maximum capacity.'
                );
            }

            $conflicting = $enclosure->animals->firstWhere('is_predator', '!=', $expectedPredator);
            if ($conflicting) {
                $validator->errors()->add(
                    'enclosure_id',
                    'This enclosure already contains a ' . ($conflicting->is_predator ? 'predator' : 'herbivore') . ', and cannot mix animal types.'
                );
            }
        });
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();
        $validated['is_predator'] = SpeciesMap::isPredator($this->input('species'));
        return $validated;
    }


    public function messages()
    {
        return [
            'name.required' => 'The dinosaur name is required.',
            'name.string' => 'The name must be a string.',
            'name.min' => 'The name must be at least 5 characters.',
            'name.max' => 'The name may not be greater than 30 characters.',

            'species.required' => 'The species is required.',
            'species.in' => 'That species is not allowed.',

            'born_at.required' => 'The birth date is required.',
            'born_at.date_format' => 'The birth date must be in the format YYYY-MM-DD.',

            'image_path.image' => 'The image must be an image file.',
            'image_path.mimes' => 'Image must be jpeg, png, jpg, or gif.',
            'image_path.max' => 'Image may not be larger than 2MB.',

            'enclosure_id.required' => 'The enclosure is required.',
            'enclosure_id.exists' => 'Selected enclosure does not exist.',
        ];
    }
}
