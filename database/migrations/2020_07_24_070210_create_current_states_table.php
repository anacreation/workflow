<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrentStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('current_states',
            function(Blueprint $table) {
                $table->id();
                $table->morphs('object');
                $table->unsignedBigInteger('state_id');
                $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
                $table->timestamps();

            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('current_states');
    }
}
