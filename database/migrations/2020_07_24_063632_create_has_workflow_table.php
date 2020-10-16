<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHasWorkflowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('has_workflow',
            function(Blueprint $table) {
                $table->string('has_workflow_object');
                $table->unsignedBigInteger('workflow_id');
                $table->foreign('workflow_id')->references('id')->on('workflows')
                      ->onDelete('cascade');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('has_workflow');
    }
}
