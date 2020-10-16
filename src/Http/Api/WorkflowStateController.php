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


use Anacreation\Workflow\Entities\State;
use Anacreation\Workflow\Entities\Workflow;
use Anacreation\Workflow\Http\Resources\StateResource;
use Anacreation\Workflow\Http\Resources\WorkflowResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkflowStateController extends Controller
{

    public function index(Workflow $workflow): JsonResponse {
        $resource = (new WorkflowResource($workflow))
            ->include([
                          'states',
                          'transitions',
                      ]);

        return response()->json($resource);
    }

    public function show(Workflow $workflow, State $state): JsonResponse {

        if($state->workflow->isNot($workflow)) {
            abort(403,
                  'invalid workflow state pair');
        }

        $workflow = new WorkflowResource($workflow);
        $state = new StateResource($state);

        return response()->json(compact('workflow',
                                        'state'));
    }

    public function store(Workflow $workflow, Request $request): JsonResponse {
        $validatedData = $this->validate($request,
                                         [
                                             'label'      => 'required',
                                             'code'       => 'required|unique:workflows',
                                             'is_initial' => 'required|boolean',
                                         ]);

        $state = $workflow->states()->create($validatedData);

        $workflow = new WorkflowResource($workflow);
        $state = new StateResource($state);

        return response()->json(compact('workflow',
                                        'state'));
    }

    public function update(Workflow $workflow, Request $request, State $state): JsonResponse {

        if($state->workflow->isNot($workflow)) {
            abort(403,
                  'Invalid workflow state pair');
        }

        $validatedData = $this->validate($request,
                                         [
                                             'label'      => 'required',
                                             'code'       => 'required|unique:workflows,code,'.$workflow->id,
                                             'is_initial' => 'required|boolean',
                                         ]);


        $state->update($validatedData);

        $workflow = new WorkflowResource($workflow);
        $state = new StateResource($state);

        return response()->json(compact('workflow',
                                        'state'));
    }

    public function destroy(Workflow $workflow, State $state): JsonResponse {
        if($state->workflow->isNot($workflow)) {
            abort(403,
                  'Invalid workflow state pair');
        }

        $state->delete();

        return response()->json('completed');
    }
}
