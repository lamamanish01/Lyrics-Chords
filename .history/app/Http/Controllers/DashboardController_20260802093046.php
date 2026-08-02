<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Collection;
use App\Models\Lyric;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSongs = Song::count();
        $totalCollections = Collection::count();
        $totalLyrics = Lyric::count();

        // Recent songs (latest 5)
        $recentSongs = Song::with('collections')->latest()->take(5)->get();

        $collections = Collection::withCount('songs')->get();

        // Songs per collection (for a simple chart)
        $collectionLabels = $collections->pluck('name')->toArray();
        $collectionData = $collections->pluck('songs_count')->toArray();

        return view('dashboard.index', compact(
            'totalSongs',
            'totalCollections',
            'totalLyrics',
            'recentSongs',
            'collections',
            'collectionLabels',
            'collectionData'
        ));
    }
}
