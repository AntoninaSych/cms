<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseChapterTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_chapter_tests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('chapter_id');
            $table->string('title', 500);
            $table->unsignedTinyInteger('type');
            $table->unsignedTinyInteger('position');
            $table->string('answer_correct', 500)->nullable();
            $table->string('answer_incorrect', 500)->nullable();
            $table->timestamps();

            $table->foreign('chapter_id')->references('id')->on('course_chapters')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_chapter_tests');
    }
}
