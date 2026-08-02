<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Collection;
use App\Services\ChordTransposer;
use Illuminate\Http\Request;

class SongController extends Controller
{
    public function index(Request $request)
    {
        $query = Song::with('collections');

        if ($search = $request->get('search')) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('artist', 'like', "%{$search}%")
                  ->orWhere('genre', 'like', "%{$search}%");
        }

        if ($collectionId = $request->get('collection')) {
            $query->whereHas('collections', fn($q) => $q->where('collections.id', $collectionId));
        }

        $songs = $query->paginate(10)->appends($request->query());
        $collections = Collection::all();
        return view('songs.index', compact('songs', 'collections'));
    }

    public function create()
    {
        $song = new Song();
        $collections = Collection::all();
        return view('songs.create', compact('song', 'collections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'  => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'album'  => 'nullable|string|max:255',
            'year'   => 'nullable|integer|min:1900|max:' . date('Y'),
            'genre'  => 'nullable|string|max:100',
            'key'    => 'nullable|string|max:10',
        ]);

        $song = Song::create($validated);
        $song->collections()->sync($request->input('collections', []));

        return redirect()->route('songs.index')->with('success', 'Song created.');
    }

    public function show(Request $request, Song $song)
    {
        $transpose = (int) $request->get('transpose', 0);
        $lyrics = $song->lyrics->map(function ($lyric) use ($transpose) {
            if ($transpose !== 0) {
                $lyric->content = ChordTransposer::transpose($lyric->content, $transpose);
            }
            return $lyric;
        });

        return view('songs.show', compact('song', 'lyrics', 'transpose'));
    }

    public function edit(Song $song)
    {
        $collections = Collection::all();
        return view('songs.edit', compact('song', 'collections'));
    }

    public function update(Request $request, Song $song)
    {
        $validated = $request->validate([
            'title'  => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'album'  => 'nullable|string|max:255',
            'year'   => 'nullable|integer|min:1900|max:' . date('Y'),
            'genre'  => 'nullable|string|max:100',
            'key'    => 'nullable|string|max:10',
        ]);

        $song->update($validated);
        $song->collections()->sync($request->input('collections', []));

        return redirect()->route('songs.index')->with('success', 'Song updated.');
    }

    public function destroy(Song $song)
    {
        $song->delete();
        return redirect()->route('songs.index')->with('success', 'Song deleted.');
    }

    public function transposeAjax(Request $request, Song $song)
    {
        $transpose = (int) $request->get('transpose', 0);
        $lyrics = $song->lyrics->map(function ($lyric) use ($transpose) {
            if ($transpose !== 0) {
                $lyric->content = ChordTransposer::transpose($lyric->content, $transpose);
            }
            return $lyric;
        });

        $html = view('songs._lyrics', compact('lyrics'))->render();
        return response()->json(['html' => $html, 'transpose' => $transpose]);
        }
}
