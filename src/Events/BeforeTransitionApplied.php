<?php

namespace Anacreation\Workflow\Events;

use Anacreation\Workflow\Entities\Transition;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BeforeTransitionApplied
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public object $entity;
    /**
     * @var \Anacreation\Workflow\Entities\Transition
     */
    public Transition $transition;

    /**
     * Create a new event instance.
     *
     * @param object                                    $entity
     * @param \Anacreation\Workflow\Entities\Transition $transition
     */
    public function __construct(object $entity, Transition $transition) {
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
