<?php

namespace Anacreation\Workflow\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TransitionRecord extends Model
{
    protected $fillable = [
        'causer_id',
        'causer_type',
        'entity_id',
        'entity_type',
        'transition_id',
    ];

    public function entity(): MorphTo {
        return $this->morphTo();
    }

    public function causer(): MorphTo {
        return $this->morphTo();
    }

    public function transition(): BelongsTo {
        return $this->belongsTo(State::class);
    }
}
