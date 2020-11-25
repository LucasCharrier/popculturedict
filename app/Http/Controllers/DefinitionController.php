<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App;
use App\Models\Definition;
use App\Models\Tag;
use App\Models\Word;
use App\Jobs\ProcessDefinitionImage;
use App\Http\Resources\Definition as DefinitionResource;
use App\Http\Resources\DefinitionCollection;
use App\Http\Resources\Word as WordResource;
use App\Http\Resources\WordCollection;

# TODO : move to utils files
function utilsEndsWith($haystack, $needle) {
    return substr_compare($haystack, $needle, -strlen($needle)) === 0;
}


class DefinitionController extends Controller
{
    public function index(Request $request)
    {
        $search =  $request->input('q');
        $character = $request->input('character');

        # TODO : sqlite that run in test does not support ILIKE,
        # we should use same db type as in prod or mock requests
        $COMPARISON_OPERATOR = App::runningUnitTests() ? 'LIKE' : 'ILIKE';

        if ($search) {
            #
            # TODO : Add a searchable field to implements fuzy search
            #
            $pieces = explode("-", $search);
            $pieces = implode(" ", $pieces);
            $definition = Definition::join('words', 'definitions.word_id', '=', 'words.id')
                ->select('words.name as wname', 'definitions.*')
                ->where('definitions.visibility', '=', config('enums.visibility')['PUBLIC'])
                ->where(function ($query) use ($search, $pieces, $COMPARISON_OPERATOR){
                    $query->where('text', $COMPARISON_OPERATOR, '%'.$search.'%')
                    ->orWhere('name', $COMPARISON_OPERATOR, '%'.$search.'%')
                    ->orWhere('name', $COMPARISON_OPERATOR, '%'.$pieces.'%')
                    ->orWhere('text', $COMPARISON_OPERATOR, '%'.$pieces.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate();
            $definition->appends(['q' => $search]);
            return new DefinitionCollection($definition);

        } else if ($character) {
            $definition = Definition::join('words', 'definitions.word_id', '=', 'words.id')
                ->select('words.name as wname', 'definitions.*')
                ->where('definitions.visibility', '=', config('enums.visibility')['PUBLIC'])
                ->where(function ($query) use ($character, $COMPARISON_OPERATOR){
                    $query->where('words.name', $COMPARISON_OPERATOR, $character.'%')
                    ->orWhere('words.name', $COMPARISON_OPERATOR, $character.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(100);
            $definition->appends(['q' => $character]);
            return new DefinitionCollection($definition);
        }
        else{
            return new DefinitionCollection(Definition::orderBy('created_at', 'desc')
                ->where('visibility', config('enums.visibility')['PUBLIC'])
                ->paginate());
        }
    }

    public function show($id)
    {
        return new DefinitionResource(Definition::findOrFail($id));
    }

    public function store(Request $request)
    {

        $this->validate(request(), [
            'name' => 'required',
            'text' => 'required',
            'exemple' => 'required'
        ]);
        
        $media_url = $request->get('media_url');

        if ($media_url && utilsEndsWith('giphy.com', parse_url($request->media_url)['host'])) {
            return response()->json(null, 401);
        }
        $word = Word::where('name', $request->get('name'))->first();
        if (!$word) {
            $word = Word::create([
                'name' => $request->get('name'),
            ]);
        }
        #
        # TODO : here we should use a transaction we want
        # this queries to run all together or roll back
        #
        $definition = Definition::create([
            'text' => $request->get('text'),
            'exemple' => $request->get('exemple'),
            'word_id' => $word->id,
            'user_id' => $request->user()->id,
            'media_url' => $media_url,
            'visibility' => config('enums.visibility')[$request->get('visibility', 'PUBLIC')]
        ]);

        if ($request->get('tags')) {
            $tags = array_unique($request->get('tags'));
            #
            # TODO : the following implementation is lighter in term of code
            # but seems to execute more queries to db, to check
            #
            // $tagIds = [];
            // foreach ($tags as $tag) {
            //     $tag = trim($tag);
            //     if ($tag == '') {
            //         continue;
            //     }
            //     $fTag = Tag::firstOrCreate([ 'text' =>  strtolower($tag)]);

            //     $tagIds[] = $fTag->id; 
            // }

            $tagsObj = Tag::query();
            foreach($tags as $tag){
                $tagsObj->orWhere('text', '=', trim(strtolower($tag)));
            }
            $tagsObj = $tagsObj->distinct()->get()->toArray();

            // identify tags that alreay exist
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
        // ProcessDefinitionImage::dispatch($definition);

        return (new DefinitionResource($definition))->response()->setStatusCode(201);
    }
    
    public function delete($id, Request $request)
    {
        $definition = Definition::findOrFail($id);
        $user = $request->user();

        if ($user->id != $definition->user()->first()['id']) {
            return response()->json(null, 401);
        }
        $definition->tags()->detach();
        $definition->delete();

        return response()->json(null, 204);
    }

}
