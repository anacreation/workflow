<?php

namespace Anacreation\Workflow\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Symfony\Component\Workflow\DefinitionBuilder;
use Symfony\Component\Workflow\MarkingStore\MethodMarkingStore;
use Symfony\Component\Workflow\Transition as Trans;
use Symfony\Component\Workflow\Workflow as WF;

class Workflow extends Model
{
    protected $fillable = [
        'label',
        'code',
    ];

    public static function build(int $workflowId) {
        $instance = static::findOrFail($workflowId);

        $definitionBuilder = new DefinitionBuilder();

        $places = $instance->states()->pluck('code')->toArray();

        $transitions = $instance->transitions->all();

        $definition = $definitionBuilder->addPlaces($places);

        foreach($transitions as $transition) {
            $definition->addTransition(new Trans($transition->code,
                                                 $transition->fromState->code,
                                                 $transition->toState->code));
        }

        $singleState = true;
        $property = 'currentState';
        $marking = new MethodMarkingStore($singleState,
                                          $property);

        return new WF($definition->build(),
                      $marking);

    }


    public function states(): HasMany {
        return $this->hasMany(State::class);
    }

    public function transitions(): HasManyThrough {
        return $this->HasManyThrough(Transition::class,
                                     State::class,
                                     'workflow_id',
                                     'from_state_id');
    }


    public function apply($object, $transition): void {
        $reflection = new \ReflectionClass($object);
        if($reflection->hasMethod('applyTransition')) {
            $object->applyTransition($transition);
        }
    }


}
