<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TagDefinitionController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Event $tag)
    {
        $definitions = $tag->definitions;
        return response()->json(['message'=>null,'data'=>$definitions],200);
    }
}
