<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class DefinitionTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Event $definition)
    {
        $tags = $definition->tags;
        return response()->json(['message'=>null,'data'=>$tags],200);
    }

}
