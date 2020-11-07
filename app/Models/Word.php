<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'user_id'
    ];

    public function definitions()
    {
        return $this->hasMany('App\Models\Definition');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
