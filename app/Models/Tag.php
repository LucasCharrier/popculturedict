<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'text'
    ];

    public function definitions(){
        return $this->belongsToMany('App\Models\Definition');
    }
}
