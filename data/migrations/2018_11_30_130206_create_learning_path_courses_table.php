<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLearningPathCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learning_path_courses', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('learning_path_id');
            $table->tinyInteger('position')->unsigned();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('learning_path_id')->references('id')->on('learning_paths')->onDelete('cascade');

            $table->unique(['course_id', 'learning_path_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('learning_path_courses');
    }
}
