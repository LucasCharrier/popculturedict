<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Definition;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection;

use App\Http\Resources\Definition as DefinitionResource;
use App\Http\Resources\DefinitionCollection;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['userDefinitions']);
    }

    public function like($id, Request $request)
    {
        #
        # TODO : investigate transactions
        #
        $definition = Definition::findOrFail($id);
        $value = $definition->reactions
            ->where('pivot.user_id', $request->user()->id)
            ->pluck('pivot.reaction_type')->unique()->first();
        if ($value == config('enums.reaction_type')['LIKE']) {
            return response()->json(null, 200);
        } else if ($value == config('enums.reaction_type')['DISLIKE']) {
            $definition->dislike = $definition->dislike - 1;
        }
        $definition->like = $definition->like + 1;
        $definition->save();
        $request->user()->reactions()->sync([
            $id => [
                'reaction_type' => config('enums.reaction_type')['LIKE']
            ]
        ], false);
    }

    public function dislike($id, Request $request)
    {
        $definition = Definition::findOrFail($id);
        $value = $definition->reactions
            ->where('pivot.user_id',$request->user()->id)
            ->pluck('pivot.reaction_type')->unique()->first();

        if ($value == config('enums.reaction_type')['DISLIKE']) {
            return response()->json(null, 200);
        } else if ($value == config('enums.reaction_type')['LIKE']) {
            $definition->like = $definition->like - 1;
        }
        $definition->dislike = $definition->dislike + 1;
        $definition->save();
        $request->user()->reactions()->sync([
            $id => [
                'reaction_type' => config('enums.reaction_type')['DISLIKE']
            ]
        ], false);
    }

    public function userDefinitions($id, Request $request)
    {
        $definition = User::findOrFail($id)->definitions()->orderBy('created_at', 'desc');
        if (!$request->user() || $request->user()->id != $id) {
            $definition->where(
                'definitions.visibility','=', config('enums.visibility')['PUBLIC']
            );
        }
        return new DefinitionCollection($definition->paginate());
    }
}
