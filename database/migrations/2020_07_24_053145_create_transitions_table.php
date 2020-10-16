<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('transitions',
            function(Blueprint $table) {
                $table->id();
                $table->string('label');
                $table->string('code');
                $table->unsignedBigInteger('from_state_id');
                $table->foreign('from_state_id')->references('id')->on('states')
                      ->onDelete('cascade');
                $table->unsignedBigInteger('to_state_id');
                $table->foreign('to_state_id')->references('id')->on('states')
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
        Schema::dropIfExists('transitions');
    }
}
