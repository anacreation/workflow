<?php
/**
 * Author: Xavier Au
 * Date: 24/7/2020
 * Time: 3:16 PM
 */

namespace Anacreation\Workflow\Services;


use Anacreation\Workflow\Entities\Workflow;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\SupportStrategy\InstanceOfSupportStrategy;

class WorkflowRegistry
{
    private static $registry;

    private function __construct() {

    }

    /**
     * @return mixed
     */
    public static function getRegistry() {
        if(self::$registry === null) {
            $registry = new Registry();
            $records = DB::table('has_workflow')->get();
            $records->each(fn($record
            ) => $registry->addWorkflow(Workflow::build($record->workflow_id),
                                        new InstanceOfSupportStrategy($record->has_workflow_object)));

            self::$registry = $registry;
        }

        return self::$registry;
    }

}
