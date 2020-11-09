<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DefinitionUser extends Pivot
{
    use HasFactory;

    public function reaction()
    {
        return $this->belongsTo('App\Models\Uses');
    }
}
