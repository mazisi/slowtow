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
        Schema::create('temporal_licences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('liquor_licence_number')->nullable();
            $table->text('premises_description')->nullable();
            $table->date('start_date')->nullable();
            $table->time('start_time')->nullable();
            $table->date('end_date')->nullable();
            $table->time('end_time')->nullable();
            $table->enum('province',['Eastern Cape','Free State','Gauteng','KwaZulu-Natal','Limpopo','Mpumalanga','Northern Cape','North West','Western Cape']);
            $table->string('municipality')->nullable();
            $table->string('extent_of_financial_honest')->nullable();
            $table->string('slug');
            $table->softDeletes();
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
        Schema::table('temporaly_licences', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
