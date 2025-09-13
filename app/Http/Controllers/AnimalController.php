<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use App\Support\SpeciesMap;
use App\Models\Enclosure;
use App\Http\Requests\AnimalRequest;

class AnimalController extends Controller
{

    public function index()
    {
        $animals = Animal::onlyTrashed()
            ->with('enclosure')
            ->orderBy('deleted_at', 'desc')
            ->get();
        
        $enclosures = Enclosure::all();

        return view('animals.index', compact('animals', 'enclosures'));
    }

    
    public function create()
    {
        $enclosures = Enclosure::all();
        $speciesList = SpeciesMap::species();
        return view('animals.form', compact('enclosures', 'speciesList'));
    }

    public function store(AnimalRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $path = $image->store('animal_images', 'public');
            $data['image_path'] = 'storage/' . $path;
        }
    
        $animal = Animal::create($data);
    
        return redirect()
            ->route('enclosures.show', $animal->enclosure_id)
            ->with('success', 'Animal created successfully.');
    }

    public function edit(Animal $animal)
    {
        $enclosures = Enclosure::all();
        $animal = Animal::findOrFail($animal->id);
        $speciesList = SpeciesMap::species();
        return view('animals.form', compact('animal', 'enclosures', 'speciesList'));
    }

    public function update(AnimalRequest $request, Animal $animal)
    {
        $data = $request->validated();

        if ($request->hasFile('image_path')) {
            if ($animal->image_path && file_exists(public_path($animal->image_path))) {
                unlink(public_path($animal->image_path));
            }

            $image = $request->file('image_path');
            $path = $image->store('animal_images', 'public');
            $data['image_path'] = 'storage/' . $path;
        } else {

            $data['image_path'] = $animal->image_path;
        }

        $animal->update($data);

        return redirect()
            ->route('enclosures.show', $animal->enclosure_id)
            ->with('success', 'Animal updated successfully.');
    }

    public function destroy(Animal $animal)
    {
        $enclosureId = $animal->enclosure_id;

        $animal->delete();

        return redirect()
            ->route('enclosures.show', $enclosureId)
            ->with('success', 'Animal removed from enclosure.');
    }


 public function restore(Request $request, $id)
{
    $request->validate([
        'enclosure_id' => 'required|exists:enclosures,id',
    ]);

    $animal = Animal::withTrashed()->findOrFail($id);
    $enclosure = Enclosure::with('animals')->findOrFail($request->enclosure_id);

    if ($enclosure->animals()->count() >= $enclosure->limit) {
        return back()->withErrors([
            'enclosure_id' => 'This enclosure is already at full capacity.'
        ])->withInput();
    }
    if ($enclosure->is_predator !== $animal->is_predator) {
        return back()->withErrors([
            'enclosure_id' => 'This enclosure is for ' . ($enclosure->is_predator ? 'predators' : 'herbivores') . '.'
        ])->withInput();
    }

    $animal->enclosure_id = $enclosure->id;
    $animal->restore();

    return redirect()
        ->route('animals.index')
        ->with('success', 'Animal restored successfully!');
}



    
}
