<?php
/**
 * Author: Xavier Au
 * Date: 24/7/2020
 * Time: 3:49 PM
 */

namespace Anacreation\Workflow\Traits;


use Anacreation\Workflow\Entities\CurrentState;
use Anacreation\Workflow\Entities\State;
use Anacreation\Workflow\Entities\Transition;
use Anacreation\Workflow\Entities\Workflow;
use Anacreation\Workflow\Services\WorkflowRegistry;
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

    public function getAllTransitions(): Collection {
        if($workflow = $this->getWorkflow()) {
            return $workflow->transitions;
        }

        return collect([]);

    }

    protected function currentState(): MorphOne {
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
        if($workflow = $this->getWorkflow()) {
            $state = $workflow->states()->where('is_initial',
                                                true)->firstOrFail();

            $this->currentState()->create(['state_id' => $state->id]);
        }
    }

    public function applyTransition($transition = null): self {

        if($transition === null) {
            throw new InvalidArgumentException("Invalid transition!");
        }

        if($transition instanceof Transition) {
            $registry = WorkflowRegistry::getRegistry();
            $workflow = $registry->get($this);
            $workflow->apply($this,
                             $transition->code);

            return $this;
        }

        if(is_string($transition)) {
            $this->applyTransition($this->getTransitions()->first(fn(
                $t) => $t->code === $transition));
        }
    }
}
