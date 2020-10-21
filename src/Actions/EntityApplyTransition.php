<?php

namespace Anacreation\Workflow\Actions;

use Anacreation\Workflow\Contracts\HasWorkflowInterface;
use Anacreation\Workflow\Entities\Transition;
use Anacreation\Workflow\Services\WorkflowRegistry;
use Anacreation\WorkflowEvents\BeforeTransitionApplied;
use Anacreation\WorkflowEvents\TransitionApplied;

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
        $workflow->apply($entity,
                         $transition->code);

        event(new TransitionApplied($entity,
                                    $transition));
    }
}
