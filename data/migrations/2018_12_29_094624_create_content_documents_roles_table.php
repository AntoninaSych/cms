<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentDocumentsRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_documents_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('content_document_id');
            $table->unsignedInteger('role_id');
            $table->timestamps();

            $table->unique(['content_document_id', 'role_id']);

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
        Schema::dropIfExists('content_documents_roles');
    }
}
