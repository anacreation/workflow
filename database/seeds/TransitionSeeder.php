<?php

use Anacreation\Workflow\Entities\Transition;
use Illuminate\Database\Seeder;

class TransitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $transitions = [
            [
                'label'         => 'Review',
                'code'          => 'review',
                'from_state_id' => 1,
                'to_state_id'   => 2,
            ],
            [
                'label'         => 'Publish',
                'code'          => 'publish',
                'from_state_id' => 2,
                'to_state_id'   => 3,
            ],
            [
                'label'         => 'Reject',
                'code'          => 'rejected',
                'from_state_id' => 2,
                'to_state_id'   => 4,
            ],
        ];

        foreach($transitions as $transition) {
            Transition::create($transition);
        }
    }
}
