<?php

namespace Anacreation\Workflow\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transition extends Model
{
    protected $fillable = [
        'label',
        'code',
        'from_state_id',
        'to_state_id',
    ];

    public function fromState(): BelongsTo {
        return $this->BelongsTo(State::class,
                                'from_state_id');
    }

    public function toState(): BelongsTo {
        return $this->BelongsTo(State::class,
                                'to_state_id');
    }

    public function getWorkflowAttribute(): Workflow {
        return $this->fromState->workflow;
    }
}
