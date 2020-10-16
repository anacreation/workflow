<?php

namespace Anacreation\Workflow\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CurrentState extends Model
{
    protected $fillable = [
        'object_type',
        'object_id',
        'state_id',
    ];

    public function object(): MorphTo {
        return $this->morphTo();
    }

    public function state(): BelongsTo {
        return $this->belongsTo(State::class);
    }
}
