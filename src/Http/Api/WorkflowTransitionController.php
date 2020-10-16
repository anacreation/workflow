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


use Anacreation\Workflow\Entities\Transition;
use Anacreation\Workflow\Entities\Workflow;
use Anacreation\Workflow\Http\Resources\TransitionResource;
use Anacreation\Workflow\Http\Resources\WorkflowResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkflowTransitionController extends Controller
{

    public function index(Workflow $workflow): JsonResponse {
        $workflow = new WorkflowResource($workflow);
        $transitions = TransitionResource::collection($workflow->transitions);

        return response()->json(compact('workflow',
                                        'transitions'));
    }

    public function show(Workflow $workflow, Transition $transition): JsonResponse {

        if($transition->workflow->isNot($workflow)) {
            abort(403,
                  'invalid workflow state pair');
        }

        $workflow = new WorkflowResource($workflow);
        $state = new TransitionResource($transition);

        return response()->json(compact('workflow',
                                        'state'));
    }

    public function store(Workflow $workflow, Request $request): JsonResponse {
        $validatedData = $this->validate($request,
                                         [
                                             'label'         => 'required',
                                             'code'          => 'required|unique:transitions',
                                             'from_state_id' => [
                                                 'required',
                                                 Rule::exists('states',
                                                              'id')
                                                     ->where(fn($q) => $q->where('workflow_id',
                                                                                 $workflow->id)),
                                             ],
                                             'to_state_id'   => [
                                                 'required',
                                                 Rule::exists('states',
                                                              'id')
                                                     ->where(fn($q) => $q->where('workflow_id',
                                                                                 $workflow->id)),
                                             ],
                                         ]);

        $transition = $workflow->transitions()->create($validatedData);

        $workflow = new WorkflowResource($workflow);
        $transition = new TransitionResource($transition);

        return response()->json(compact('workflow',
                                        'transition'));
    }

    public function update(
        Workflow $workflow, Request $request, Transition $transition): JsonResponse {

        if($transition->workflow->isNot($workflow)) {
            abort(403,
                  'Invalid workflow transition pair');
        }

        $validatedData = $this->validate($request,
                                         [
                                             'label'         => 'required',
                                             'code'          => 'required|unique:transitions,code,'.$transition->id,
                                             'from_state_id' => [
                                                 'required',
                                                 Rule::exists('states',
                                                              'id')
                                                     ->where(fn($q) => $q->where('workflow_id',
                                                                                 $workflow->id)),
                                             ],
                                             'to_state_id'   => [
                                                 'required',
                                                 Rule::exists('states',
                                                              'id')
                                                     ->where(fn($q) => $q->where('workflow_id',
                                                                                 $workflow->id)),
                                             ],
                                         ]);


        $transition->update($validatedData);

        $workflow = new WorkflowResource($workflow);
        $transition = new TransitionResource($transition);

        return response()->json(compact('workflow',
                                        'transition'));
    }

    public function destroy(Workflow $workflow, Transition $transition): JsonResponse {
        if($transition->workflow->isNot($workflow)) {
            abort(403,
                  'Invalid workflow transition pair');
        }

        $transition->delete();

        return response()->json('completed');
    }
}
