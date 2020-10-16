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
 * @package     ${PACKAGE}
 * @Date        : 16/10/2020
 * @copyright   Copyright (c) A & A Creation (https://anacreation.com/)
 */

use Anacreation\Workflow\Entities\Workflow;
use Anacreation\Workflow\Http\Api\WorkflowController;
use Anacreation\Workflow\Http\Api\WorkflowStateController;
use Anacreation\Workflow\Http\Api\WorkflowTransitionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::group([
                 'middleware' => 'api',
                 'prefix'     => 'api',
             ],
    function() {


        Route::group([
                         'middleware' => config('workflow.route.middleware',
                                                []),
                         'prefix'     => config('workflow.route.prefix',
                                                ''),
                     ],
            function() {


                Route::get('/workflows',
                           WorkflowController::class."@index");
                Route::put('/workflows/{workflow}',
                           WorkflowController::class."@update");
                Route::delete('/workflows/{workflow}',
                              WorkflowController::class."@destroy");
                Route::post('/workflows',
                            WorkflowController::class."@store");
                Route::get('/workflows/{workflow}',
                           WorkflowController::class."@show");


                Route::get('/workflows/{workflow}/states',
                           WorkflowStateController::class."@index");
                Route::put('/workflows/{workflow}/states/{state}',
                           WorkflowStateController::class."@update");
                Route::delete('/workflows/{workflow}}/states/{state}',
                              WorkflowStateController::class."@destroy");
                Route::post('/workflows/{workflow}/states',
                            WorkflowStateController::class."@store");
                Route::get('/workflows/{workflow}/states/{state}',
                           WorkflowStateController::class."@show");


                Route::get('/workflows/{workflow}/transitions',
                           WorkflowTransitionController::class."@index");
                Route::put('/workflows/{workflow}/transitions/{transition}',
                           WorkflowTransitionController::class."@update");
                Route::delete('/workflows/{workflow}}/transitions/{transition}',
                              WorkflowTransitionController::class."@destroy");
                Route::post('/workflows/{workflow}/transitions',
                            WorkflowTransitionController::class."@store");
                Route::get('/workflows/{workflow}/transitions/{transition}',
                           WorkflowTransitionController::class."@show");

                Route::post('/workflows/{workflow}/entities',
                    function(Request $request, Workflow $workflow) {
                        if($entity = $request->get('entity') and
                           DB::table('has_workflow')
                             ->where('has_workflow_object',
                                     $entity)
                             ->where('workflow_id',
                                     $workflow->id)
                             ->first() === null) {

                            DB::table('has_workflow')
                              ->insert([
                                           'has_workflow_object' => $entity,
                                           'workflow_id'         => $workflow->id,
                                       ]);

                            return response()->json('completed');

                        }

                        return response()->json('failed',
                                                403);

                    });

                Route::delete('/workflows/{workflow}/entities/{id}',
                    function(Workflow $workflow, int $id) {
                        if($record = DB::table('has_workflow')
                                       ->where('id',
                                               $id)
                                       ->where('workflow_id',
                                               $workflow->id)
                                       ->first()) {

                            $record->delete();
                        }

                        return response()->json('completed');

                    });

            });

    });
