<?php

use Anacreation\Workflow\Entities\Workflow;
use Illuminate\Database\Seeder;

class WorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $workflows = [
            [
                'label' => 'Publishing Workflow',
                'code'  => 'publishing',
            ],
        ];

        foreach($workflows as $workflow) {
            Workflow::create($workflow);
        }
    }
}
