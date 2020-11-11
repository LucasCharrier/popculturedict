<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Word as WordResource;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\TagCollection;
use Illuminate\Support\Facades\Auth;

class Definition extends JsonResource
{
    /**p
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id'         => $this->id,
            'text'       => $this->text,
            'exemple'       => $this->exemple,
            'user'    => new UserResource($this->user),
            'word' => new WordResource($this->word),
            'tags' => new TagCollection($this->tags),
            'like' => (int) $this->like,
            'dislike' => (int) $this->dislike,
            'media_url' => $this->media_url,
            'visibility' => $this->visibility,
            // 'user_reaction' => $this->reactions->pivot() wherePivot('user_id', Auth::id())->pluck('pivot.reaction_type')->unique()->first(),
            'user_reaction' => $this->reactions->where('pivot.user_id', Auth::id())->pluck('pivot.reaction_type')->unique()->first(),
            
            // new Reaction($this->reactions()->wherePivot('user_id', Auth::id())->withPivot('reaction_type')->reaction_type),
            // 'answers'    => (int) $this->answers,
            // 'points'     => (int) $this->points,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
