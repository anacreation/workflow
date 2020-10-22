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

use Anacreation\Workflow\Http\Api\WorkflowController;
use Anacreation\Workflow\Http\Api\WorkflowEntityController;
use Anacreation\Workflow\Http\Api\WorkflowStateController;
use Anacreation\Workflow\Http\Api\WorkflowTransitionController;
use Illuminate\Support\Facades\Route;

Route::group([
                 'middleware' => ['api'],
                 'prefix'     => 'api',
                 'namespace'  => null,
             ],
    function() {


        Route::group([
                         'middleware' => config('workflow.route.middleware') ?
                             [config('workflow.route.middleware')]: [],
                         'prefix'     => config('workflow.route.prefix',
                                                null),
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
                            WorkflowEntityController::class."@index");
                Route::delete('/workflows/{workflow}/entities/{id}',
                              WorkflowEntityController::class."@destroy");

            });

    });
