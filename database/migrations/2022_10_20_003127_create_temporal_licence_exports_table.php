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
        Schema::create('temporal_licence_exports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('event_name');
            $table->string('applicant');
            $table->string('event_dates')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('payment_date')->nullable();
            $table->string('licence_number')->nullable();
            $table->string('date_lodged')->nullable();
            $table->string('proof_of_lodgement')->nullable();
            $table->string('date_granted')->nullable();
            $table->string('current_status')->nullable();
            $table->longText('notes')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('temporal_licence_exports');
    }
};
