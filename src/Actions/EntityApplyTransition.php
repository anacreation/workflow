<?php

namespace Anacreation\Workflow\Actions;

use Anacreation\Workflow\Contracts\HasWorkflowInterface;
use Anacreation\Workflow\Entities\Transition;
use Anacreation\Workflow\Entities\TransitionRecord;
use Anacreation\Workflow\Events\BeforeTransitionApplied;
use Anacreation\Workflow\Events\TransitionApplied;
use Anacreation\Workflow\Services\WorkflowRegistry;
use Illuminate\Support\Facades\DB;

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
class EntityApplyTransition
{
    public static function apply(HasWorkflowInterface $entity, Transition $transition): void {

        event(new BeforeTransitionApplied($entity,
                                          $transition));

        $registry = WorkflowRegistry::getRegistry();
        $workflow = $registry->get($entity);

        DB::beginTransaction();

        try {

            $workflow->apply($entity,
                             $transition->code);
            TransitionRecord::create([
                                         'causer_type'   => get_class(auth()->user()),
                                         'causer_id'     => auth()->id(),
                                         'entity_type'   => get_class($entity),
                                         'entity_id'     => $entity->id,
                                         'transition_id' => $transition->id,
                                     ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


        event(new TransitionApplied($entity,
                                    $transition));

    }
}
