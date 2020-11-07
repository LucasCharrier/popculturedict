<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection;

use App\Http\Resources\Definition as DefinitionResource;
use App\Http\Resources\DefinitionCollection;

class UserController extends Controller
{
    public function index()
    {
        return new UserCollection(User::all());
    }

    public function show($id)
    {
        return new UserResource(User::findOrFail($id));
    }

    public function userDefinitions($id)
    {
        return new DefinitionCollection(User::findOrFail($id)->definitions()->orderBy('created_at', 'desc')->paginate());
    }
}
