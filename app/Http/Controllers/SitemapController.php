<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        $words = Word::all()->first();

        return response()->view('sitemap.index', [
            'words' => $words
        ])->header('Content-Type', 'text/xml');
    }
      
    public function words()
    {
        $words= Word::latest()->get();
        return response()->view('sitemap.words', [
            'words' => $words,
        ])->header('Content-Type', 'text/xml');
    }
}
