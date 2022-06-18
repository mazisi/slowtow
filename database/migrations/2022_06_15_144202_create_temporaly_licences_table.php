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
            $table->string('belongs_to')->nullable();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('consultant_id')->constrained()->onDelete('cascade');
            $table->string('liquor_licence_number')->nullable();
            $table->string('active')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
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
