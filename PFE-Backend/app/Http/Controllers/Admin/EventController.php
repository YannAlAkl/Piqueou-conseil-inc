<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $evenements = Event::with('creator')
            ->orderBy('start_date', 'desc')
            ->paginate(15);

        return view('admin.events.index', compact('evenements'));
    }

    public function create()
    {
        $evenement = null;
        return view('admin.events.create', compact('evenement'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title'       => 'required|string|max:255',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'schedule'    => 'nullable|string|max:255',
            'location'    => 'nullable|string|max:255',
            'description' => 'required|string',
            'link_url'    => 'nullable|url|max:255',
            'link_label'  => 'nullable|string|max:255',
            'is_published' => 'boolean',
        ]);

        $validatedData['created_by'] = auth()->id();
        $validatedData['is_published'] = $request->boolean('is_published');

        Event::create($validatedData);

        return redirect()->route('admin.events.create')->with('success', 'Événement créé avec succès.');
    }

    public function show($id)
    {
        $evenement = Event::with('creator')->findOrFail($id);
        return view('admin.events.show', compact('evenement'));
    }

    public function edit($id)
    {
        $evenement = Event::findOrFail($id);
        return view('admin.events.edit', compact('evenement'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title'       => 'required|string|max:255',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'schedule'    => 'nullable|string|max:255',
            'location'    => 'nullable|string|max:255',
            'description' => 'required|string',
            'link_url'    => 'nullable|url|max:255',
            'link_label'  => 'nullable|string|max:255',
            'is_published' => 'boolean',
        ]);

        $validatedData['is_published'] = $request->boolean('is_published');

        $evenement = Event::findOrFail($id);
        $evenement->update($validatedData);

        return redirect()->route('admin.events.index')->with('success', 'Événement mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $evenement = Event::findOrFail($id);
        $evenement->delete();

        return redirect()->route('admin.events.index')->with('success', 'Événement supprimé avec succès.');
    }
}
