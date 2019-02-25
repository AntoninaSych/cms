<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreateCourseChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_chapters', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_id');
            $table->string('title', 500);
            $table->string('subtitle', 500)->nullable();
            $table->json('content')->nullable();
            $table->unsignedTinyInteger('type');
            $table->string('extra_link')->nullable();
            NestedSet::columns($table);
            $table->unsignedTinyInteger('position');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_chapters');
    }
}
