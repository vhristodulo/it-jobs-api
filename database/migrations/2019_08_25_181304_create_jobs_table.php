<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id')->unsigned();

            $table->integer('job_category_id')->unsigned();
            $table->integer('job_type_id')->unsigned();
            $table->integer('job_location_id')->unsigned();

            $table->string('title');
            $table->text('description');

            $table->dateTime('application_deadline');

            $table->string('name');
            $table->string('email');
            $table->string('link');

            $table->boolean('highlighted');
            $table->boolean('deleted');

            $table->timestamps();

            $table->foreign('job_category_id')->references('id')->on('job_categories');
            $table->foreign('job_type_id')->references('id')->on('job_types');
            $table->foreign('job_location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
