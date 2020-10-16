<?php

use Anacreation\Workflow\Entities\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $states = [
            [
                'label'       => 'Draft',
                'code'        => 'draft',
                'workflow_id' => 1,
                'is_initial'  => true,
            ],
            [
                'label'       => 'Reviewed',
                'code'        => 'reviewed',
                'workflow_id' => 1,
            ],
            [
                'label'       => 'Published',
                'code'        => 'published',
                'workflow_id' => 1,
            ],
            [
                'label'       => 'Rejected',
                'code'        => 'rejected',
                'workflow_id' => 1,
            ],
        ];

        foreach($states as $state) {
            State::create($state);
        }

    }
}
