<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licence_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licence_id')->constrained()->onDelete('cascade');
            $table->string('document_name')->nullable();
            $table->string('document_type')->nullable();
            $table->integer('num')->nullable();
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
        Schema::dropIfExists('licence_documents');
    }
};
