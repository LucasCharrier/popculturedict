<?php

namespace App\Http\Controllers;
use App\Models\Tag;
use App\Http\Resources\Tag as TagResource;
use App\Http\Resources\TagCollection;

use App\Http\Resources\Definition as DefinitionResource;
use App\Http\Resources\DefinitionCollection;

use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new TagCollection(Tag::all());
    }

    public function userDefinitions($id, Request $request)
    {
        $definition = Tag::findOrFail($id)->definitions()->orderBy('created_at', 'desc');
        $definition->where('definitions.visibility', '=', config('enums.visibility')['PUBLIC']);
        return new DefinitionCollection($definition->paginate());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return new TagResource(Tag::findOrFail($id));
    }
}
