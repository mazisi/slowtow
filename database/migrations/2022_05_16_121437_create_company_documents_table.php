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
        Schema::create('company_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->boolean('sars_certificate_valid')->nullable();
            $table->date('sars_certificate_date')->nullable();
            $table->string('gatla_valid')->nullable();
            $table->date('gatla_date')->nullable();
            $table->string('gatla_certificate')->nullable();
            $table->string('bee_status')->nullable();
            $table->string('cipc_notice_of_incorporation')->nullable();
            $table->string('cipc_memorandum_of_incorporation')->nullable();
            $table->string('transfer_certificate')->nullable();
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
        Schema::dropIfExists('company_documents');
    }
};
