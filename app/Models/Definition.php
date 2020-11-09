<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Definition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'word_id',
        'text',
        'user_id',
        'exemple'
    ];

    public function word()
    {
        return $this->belongsTo('App\Models\Word');
    }

    public function comments()
    {
        return $this->belongsToMany('App\Models\Comment');
    }

    public function reactions()
    {
        return $this->belongsToMany('App\Models\User')->using('App\Models\DefinitionUser')->withPivot([
            'reaction_type'
        ]);;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function tags(){
        return $this->belongsToMany('App\Models\Tag');
    }
}
