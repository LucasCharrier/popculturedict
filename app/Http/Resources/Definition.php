<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Word as WordResource;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\TagCollection;

class Definition extends JsonResource
{
    /**
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
            // 'answers'    => (int) $this->answers,
            // 'points'     => (int) $this->points,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
