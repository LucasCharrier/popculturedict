<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Word;
use App\Http\Resources\Word as WordResource;
use App\Http\Resources\WordCollection;

use App\Models\Tag;

use App\Models\Definition;
use App\Http\Resources\Definition as DefinitionResource;
use App\Http\Resources\DefinitionCollection;

class DefinitionController extends Controller
{
    public function index(Request $request)
    {
        $search =  $request->input('q');
        if($search!=""){
            $definition = Definition::join('words', 'definitions.word_id', '=', 'words.id')
                ->select('words.name as wname', 'definitions.*')
                ->where(function ($query) use ($search){
                    $query->where('text', 'ILIKE', '%'.$search.'%')
                    ->orWhere('name', 'ILIKE', '%'.$search.'%');
                    // ->where('words.name', 'like', '%'.$search.'%');
                    // ->orWhere('words.name', 'like', '%'.$search.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate();
            $definition->appends(['q' => $search]);
            return new DefinitionCollection($definition);
        }
        else{
            return new DefinitionCollection(Definition::orderBy('created_at', 'desc')->paginate());
        }
    }

    public function show($id)
    {
        return new DefinitionResource(Definition::findOrFail($id));
    }
    /*
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate(request(), [
            'name' => 'required',
            'text' => 'required',
            'exemple' => 'required'
        ]);

        function endsWith($haystack, $needle) {
            return substr_compare($haystack, $needle, -strlen($needle)) === 0;
        }
        
        $media_url = $request->get('media_url');

        if ($media_url && endsWith('giphy.com', parse_url($request->media_url)['host'])) {
            return response()->json(null, 401);
        }
        $word = Word::where('name', $request->get('name'))->first();
        // $output->writeln('content of word' . $word);
        if (!$word) {
            // $output->writeln('no word');

            $word = Word::create([
                'name' => $request->get('name'),
            ]);
        }

        $definition = Definition::create([
            'text' => $request->get('text'),
            'exemple' => $request->get('exemple'),
            'word_id' => $word->id,
            'user_id' => $request->user()->id,
            'media_url' => $media_url
        ]);
        // $func = function($value) {
        //     return ['text', 'LIKE', '%'.strtolower($value).'%'];
        // };
        $tags = array_unique($request->get('tags'));
        if ($tags) {
            $tagsObj = Tag::query();
            foreach($tags as $tag){
                $tagsObj->orWhere('text', '=', trim(strtolower($tag)));
            }
            $tagsObj1 = $tagsObj->distinct()->get();

            $tagsObj = $tagsObj1->toArray();

            //$output->writeln('content of word' . implode("|",$tagsObj));
            $tagIds = array_map(function ($a) { return $a['id']; }, $tagsObj);
            $tagNames = array_map(function ($a) { return strtolower($a['text']); }, $tagsObj);
            $tagsToCreate = array_filter($tags, function ($a) use ($tagNames) { 
                $found = false;
                foreach ($tagNames as $value) {
                    if (strtolower($value) === strtolower($a)) {
                        $found = true;
                    }
                }
                if ($found) {
                    return false;
                }
                return true;
            });

            $definition->tags()->sync($tagIds, false);
            $definition->tags()->createMany(array_map(function ($a) { return ['text' => $a]; }, $tagsToCreate));

        }
        return (new DefinitionResource($definition))->response()->setStatusCode(201);
    }

    public function delete($id, Request $request)
    {
        $definition = Definition::findOrFail($id);
        $user = $request->user();
        // $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        // $out->writeln('toot'.$definition->user()->first()['id']);

        if ($user->id != $definition->user()->first()['id']) {
            return response()->json(null, 401);
        }
        $definition->tags()->detach();
        $definition->delete();

        return response()->json(null, 204);
    }

}
