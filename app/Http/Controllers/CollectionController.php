<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CollectionController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:view collections', only: ['index', 'show']),
            new Middleware('permission:create collections', only: ['create', 'store']),
            new Middleware('permission:edit collections', only: ['edit', 'update']),
            new Middleware('permission:delete collections', only: ['destroy']),
        ];
    }

    public function index()
    {
        $collections = Collection::withCount('songs')->get();
        return view('collections.index', compact('collections'));
    }

    public function create()
    {
        $songs = Song::orderBy('title')->get();
        return view('collections.create', compact('songs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'required|string|unique:collections,slug',
            'description' => 'nullable|string',
            'color'       => 'nullable|string|max:7',
            'songs'       => 'array|exists:songs,id',
        ]);

        $collection = Collection::create($validated);
        $collection->songs()->sync($request->input('songs', []));

        return redirect()->route('collections.index')->with('success', 'Collection created.');
    }

    public function edit(Collection $collection)
    {
        $songs = Song::orderBy('title')->get();
        return view('collections.edit', compact('collection', 'songs'));
    }

    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'required|string|unique:collections,slug,' . $collection->id,
            'description' => 'nullable|string',
            'color'       => 'nullable|string|max:7',
            'songs'       => 'array|exists:songs,id',
        ]);

        $collection->update($validated);
        $collection->songs()->sync($request->input('songs', []));

        return redirect()->route('collections.index')->with('success', 'Collection updated.');
    }

    public function destroy(Collection $collection)
    {
        $collection->delete();
        return redirect()->route('collections.index')->with('success', 'Collection deleted.');
    }
}
