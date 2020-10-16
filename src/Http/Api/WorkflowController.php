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
use Anacreation\Workflow\Http\Resources\WorkflowResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkflowController extends Controller
{

    public function index(): JsonResponse {
        return response()->json(WorkflowResource::collection(Workflow::all()));
    }

    public function show(Workflow $workflow): JsonResponse {

        $resource = (new WorkflowResource($workflow))
            ->include([
                          'states',
                          'transitions',
                      ]);

        return response()->json($resource);
    }

    public function store(Request $request): JsonResponse {
        $validatedData = $this->validate($request,
                                         [
                                             'label' => 'required',
                                             'code'  => 'required|unique:workflows',
                                         ]);


        return response()->json(new WorkflowResource(Workflow::create($validatedData)));
    }

    public function update(Request $request, Workflow $workflow): JsonResponse {
        $validatedData = $this->validate($request,
                                         [
                                             'label' => 'required',
                                             'code'  => 'required|unique:workflows,code,'.$workflow->id,
                                         ]);

        $workflow->update($validatedData);

        return response()->json(new WorkflowResource($workflow));
    }

    public function destroy(Workflow $workflow): JsonResponse {
        $workflow->delete();

        return response()->json('completed');
    }
}
