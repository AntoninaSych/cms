<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLearnpathTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learning_paths', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('title', 500);
            $table->string('subtitle', 500)->nullable();
            $table->tinyInteger('status')->unsigned();
            $table->string('language', 3);
            $table->json('description')->nullable();
            $table->tinyInteger('points')->unsigned();
            $table->string('code')->nullable();
            $table->jsonb('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('learning_paths');
    }
}
