<?php

namespace App\Http\Controllers;

use App\Helpers\ChordFormatter;
use App\Models\Lyric;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LyricController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:view lyrics', only: ['index', 'show']),
            new Middleware('permission:create lyrics', only: ['create', 'store']),
            new Middleware('permission:edit lyrics', only: ['edit', 'update']),
            new Middleware('permission:delete lyrics', only: ['destroy']),
        ];
    }

    public function create(Request $request)
    {
        $songId = $request->query('song_id');
        $songs = Song::pluck('title', 'id');
        return view('lyrics.create', compact('songs', 'songId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'song_id' => 'required|exists:songs,id',
            'content' => 'required|string',
            'content_nepali' => 'nullable|string',
            'section' => 'nullable|string|max:50',
        ]);

        Lyric::create($validated);
        return redirect()->route('songs.show', $validated['song_id'])
                        ->with('success', 'Lyric added.');
    }

    public function edit(Lyric $lyric)
    {
        $songs = Song::pluck('title', 'id');
        return view('lyrics.edit', compact('lyric', 'songs'));
    }

    public function update(Request $request, Lyric $lyric)
    {
        $validated = $request->validate([
            'song_id' => 'required|exists:songs,id',
            'content' => 'required|string',
            'content_nepali' => 'nullable|string',
            'section' => 'nullable|string|max:50',
        ]);

        $lyric->update($validated);
        return redirect()->route('songs.show', $validated['song_id'])
                        ->with('success', 'Lyric updated.');
    }

    public function destroy(Lyric $lyric)
    {
        $songId = $lyric->song_id;
        $lyric->delete();
        return redirect()->route('songs.show', $songId)
                         ->with('success', 'Lyric deleted.');
    }

    public function preview(Request $request)
    {
        $request->validate(['content' => 'required|string']);
        $html = ChordFormatter::toChordSheet($request->content);
        return response()->json(['html' => $html]);
    }
}
