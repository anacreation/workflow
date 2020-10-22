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
 * @package     anacreation\workflow
 * @Date        : 16/10/2020
 * @copyright   Copyright (c) A & A Creation (https://anacreation.com/)
 */

namespace Anacreation\Workflow\Http\Api;


use Anacreation\Workflow\Entities\Workflow;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkflowEntityController extends Controller
{

    public function index(Request $request, Workflow $workflow): JsonResponse {
        if($entity = $request->get('entity') and
           DB::table('has_workflow')
             ->where('has_workflow_object',
                     $entity)
             ->where('workflow_id',
                     $workflow->id)
             ->exists() === false) {

            DB::table('has_workflow')
              ->insert([
                           'has_workflow_object' => $entity,
                           'workflow_id'         => $workflow->id,
                       ]);

            return response()->json('completed');

        }

        return response()->json('failed',
                                403);
    }

    public function destroy(Workflow $workflow, int $id): JsonResponse {
        if($record = DB::table('has_workflow')
                       ->where('id',
                               $id)
                       ->where('workflow_id',
                               $workflow->id)
                       ->first()) {

            $record->delete();
        }

        return response()->json('completed');
    }
}
