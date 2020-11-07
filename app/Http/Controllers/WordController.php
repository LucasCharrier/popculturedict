<?php

namespace App\Http\Controllers;

use App\Models\Word;
use App\Http\Resources\Word as WordResource;
use App\Http\Resources\WordCollection;
use Illuminate\Http\Request;

class WordController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['show', 'index']);
    }

    public function index()
    {
        return new WordCollection(Word::all());
    }

    public function show($id)
    {
        return new WordResource(Word::findOrFail($id));
    }
}
