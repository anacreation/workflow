<?php

namespace Anacreation\Workflow;


use Anacreation\Workflow\Entities\State;
use Anacreation\Workflow\Entities\Transition;
use Anacreation\Workflow\Entities\Workflow;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class WorkflowServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom(
            __DIR__.'/../config/workflow.php',
            'workflow'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        Route::model('workflow',
                     Workflow::class);
        Route::model('state',
                     State::class);
        Route::model('transition',
                     Transition::class);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->publishes([
                             __DIR__.'/../config/workflow.php' => config_path('workflow.php'),
                         ]);
        $this->registerModelFactory();
    }

    private function registerModelFactory() {
        if( !App::environment('production')) {
            $this->app->make(Factory::class)->load(__DIR__.'/../database/factories');
        }
    }
}
