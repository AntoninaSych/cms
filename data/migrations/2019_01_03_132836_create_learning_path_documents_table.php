<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLearningPathDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learning_path_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('learning_path_id');
            $table->unsignedInteger('content_document_id');

            $table->unique(['learning_path_id', 'content_document_id'])->name('learning_path_documents_unique_idx');

            $table->foreign('learning_path_id')->references('id')->on('learning_paths')->onDelete('cascade');
            $table->foreign('content_document_id')->references('id')->on('content_documents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('learning_path_documents');
    }
}
