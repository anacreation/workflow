<?php

namespace Anacreation\Workflow\Actions;

use Anacreation\Workflow\Contracts\HasWorkflowInterface;
use Anacreation\Workflow\Entities\State;
use Anacreation\Workflow\Entities\TransitionRecord;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * A & A Creation Co.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    A & A Creation
 * @package     anacreation/workflow
 * @Date        : 21/10/2020
 * @copyright   Copyright (c) A & A Creation (https://anacreation.com/)
 */
class SetInitialState
{
    public static function execute(HasWorkflowInterface $entity): State {
        $workflow = $entity->getWorkflow();

        if($workflow === null) {
            throw new InvalidArgumentException("Entity doesn't has a workflow attached.");
        }

        /** @var State $state */
        $state = $workflow->states()->where('is_initial',
                                            true)->firstOrFail();
        DB::beginTransaction();

        try {

            $entity->currentState()->create(['state_id' => $state->id]);
            TransitionRecord::create([
                                         'causer_type'   => get_class(auth()->user()),
                                         'causer_id'     => auth()->id(),
                                         'entity_type'   => get_class($entity),
                                         'entity_id'     => $entity->id,
                                         'transition_id' => null,
                                     ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $state;

    }
}
