<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('states',
            function(Blueprint $table) {
                $table->id();
                $table->string('label');
                $table->string('code');
                $table->boolean('is_initial')->default(false);
                $table->unsignedBigInteger('workflow_id');
                $table->foreign('workflow_id')->references('id')->on('workflows')
                      ->onDelete('cascade');
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('states');
    }
}
