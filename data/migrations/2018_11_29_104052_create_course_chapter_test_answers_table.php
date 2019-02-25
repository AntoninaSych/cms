<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseChapterTestAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_chapter_test_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_chapter_test_id');
            $table->string('answer', 500);
            $table->boolean('is_correct');
            $table->unsignedTinyInteger('position');

            $table->foreign('course_chapter_test_id')->references('id')->on('course_chapter_tests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_chapter_test_answers');
    }
}
