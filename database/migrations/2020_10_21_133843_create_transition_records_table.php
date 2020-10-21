<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransitionRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('transition_records',
            function(Blueprint $table) {
                $table->id();
                $table->morphs('entity');
                $table->unsignedBigInteger('transition_id')->nullable();
                $table->foreign('transition_id')->references('id')->on('transitions');
                $table->string('causer_type')->nullable();
                $table->unsignedBigInteger('causer_id')->nullable();
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('transition_records');
    }
}
