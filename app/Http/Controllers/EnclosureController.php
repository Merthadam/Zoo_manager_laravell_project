<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enclosure;
use App\HTTP\Requests\EnclosureRequest;

class EnclosureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $enclosures = null;

        if (!$user->admin) {
            // Csak a saját kifutók
            $enclosures = $user->enclosures()
                ->with('animals')
                ->orderBy('name')
                ->paginate(5);
        }
        else{
            // Admin: minden kifutó
            $enclosures = Enclosure::with('animals')
                ->orderBy('name')
                ->paginate(5);
        }

        return view('enclosures.index', compact('enclosures'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('enclosures.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EnclosureRequest $request)
    {
        Enclosure::create($request->validated());
        return redirect()->route('enclosures.index')->with('success', 'Enclosure created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $enclosure = Enclosure::findOrFail($id);
        return view('enclosures.show', compact('enclosure'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $enclosure = Enclosure::findOrFail($id);
        return view('enclosures.form', compact('enclosure')); 
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(EnclosureRequest $request, string $id)
    {
        $enclosure = Enclosure::findOrFail($id); // ✅ fetch enclosure
        $enclosure->update($request->validated()); // ✅ now it's safe
        return redirect()->route('enclosures.index')->with('success', 'Enclosure updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $enclosure = Enclosure::findOrFail($id);// Include trashed if necessary

        foreach ($enclosure->animals as $animal) {
            // Option 1: Unassign animal from enclosure
            $animal->enclosure_id = null; // or move to a "lost" enclosure if you want
            $animal->save();

            // Then soft delete the animal
            $animal->delete();
        }

        // Now delete the enclosure itself
        $enclosure->delete(); // or forceDelete() if you want to truly kill it

        return redirect()
            ->route('enclosures.index')
            ->with('success', 'Enclosure and its animals have been archived/deleted.');
    }

}
