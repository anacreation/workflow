<?php

namespace Anacreation\Workflow\Events;

use Anacreation\Workflow\Contracts\HasWorkflowInterface;
use Anacreation\Workflow\Entities\Transition;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransitionApplied
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \Anacreation\Workflow\Entities\Transition
     */
    public Transition $transition;

    /**
     * @var \Anacreation\Workflow\Contracts\HasWorkflowInterface|object
     */
    public $entity;

    /**
     * Create a new event instance.
     *
     * @param object                                    $entity
     * @param \Anacreation\Workflow\Entities\Transition $transition
     */
    public function __construct(HasWorkflowInterface $entity, Transition $transition) {
        $this->entity = $entity;
        $this->transition = $transition;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() {
        return new PrivateChannel('channel-name');
    }
}
