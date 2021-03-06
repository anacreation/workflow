<?php
/**
 * Author: Xavier Au
 * Date: 24/7/2020
 * Time: 3:49 PM
 */

namespace Anacreation\Workflow\Traits;


use Anacreation\Workflow\Actions\EntityApplyTransition;
use Anacreation\Workflow\Actions\SetInitialState;
use Anacreation\Workflow\Entities\CurrentState;
use Anacreation\Workflow\Entities\State;
use Anacreation\Workflow\Entities\Transition;
use Anacreation\Workflow\Entities\Workflow;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

trait HasWorkflow
{
    public function getWorkflow(): ?Workflow {
        $r = DB::table('has_workflow')->where('has_workflow_object',
                                              get_class($this))->first();

        return $r === null ?
            null:
            Workflow::findOrFail($r->workflow_id);
    }

    public function getTransitions(): Collection {
        if($workflow = $this->getWorkflow()) {
            return $workflow->transitions()
                            ->where('from_state_id',
                                    $this->currentState->id)->get();
        }

        return collect([]);

    }

    public function canApplyTransition(Transition $transition): bool {

        if($workflow = $this->getWorkflow()) {
            return $workflow->transitions()
                            ->where('from_state_id',
                                    $this->currentState->id)
                            ->where('to_state_id',
                                    $transition->to_state_id)
                            ->exists();
        }

        return false;

    }

    public function getAllTransitions(): Collection {
        if($workflow = $this->getWorkflow()) {
            return $workflow->transitions;
        }

        return collect([]);

    }

    public function currentState(): MorphOne {
        return $this->morphOne(CurrentState::class,
                               'object');
    }


    public function getCurrentStateAttribute(): State {
        return $this->currentState()->first()->state;
    }

    public function getCurrentState(): string {
        return $this->currentState->code;
    }

    public function setInitialState(): void {
        SetInitialState::execute($this);
    }

    public function setCurrentState(string $stateCode): void {
        if($workflow = $this->getWorkflow()) {
            $state = $workflow->states()->where('code',
                                                $stateCode)->firstOrFail();

            $this->currentState()->update(['state_id' => $state->id]);
        }
    }

    public function applyTransition($transition = null): self {

        if($transition === null) {
            throw new InvalidArgumentException("Invalid transition!");
        }

        if($transition instanceof Transition) {
            EntityApplyTransition::apply($this,
                                         $transition);

            return $this;
        }

        if(is_string($transition)) {
            $this->applyTransition($this->getTransitions()->first(fn(
                $t) => $t->code === $transition));
        }
    }
}
