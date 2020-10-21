<?php
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

namespace Anacreation\Workflow\Contracts;


use Anacreation\Workflow\Entities\State;
use Anacreation\Workflow\Entities\Transition;
use Anacreation\Workflow\Entities\Workflow;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Collection;

interface HasWorkflowInterface
{
    public function getWorkflow(): ?Workflow;

    public function getTransitions(): Collection;

    public function canApplyTransition(Transition $transition): bool;

    public function getAllTransitions(): Collection;

    public function currentState(): MorphOne;

    public function getCurrentStateAttribute(): State;

    public function getCurrentState(): string;

    public function setInitialState(): void;

    public function applyTransition($transition = null): self;
}
