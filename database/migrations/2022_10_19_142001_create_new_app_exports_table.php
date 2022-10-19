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
        Schema::create('new_app_exports', function (Blueprint $table) {
            $table->id();
            $table->string('trading_name');
            $table->string('licence_type');
            $table->string('licence_number')->nullable();
            $table->string('province');
            $table->string('deposit_invoice')->nullable();
            $table->string('deposit_paid')->nullable();
            $table->string('date_logded')->nullable();
            $table->string('proof_of_lodgement')->nullable();
            $table->string('activation_fee_paid')->nullable();
            $table->string('final_invoice')->nullable();
            $table->string('full_payment')->nullable();
            $table->string('date_granted')->nullable();
            $table->string('current_status')->nullable();
            $table->string('focus_for_the_week')->nullable();
            $table->longText('notes');
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
        Schema::dropIfExists('new_app_exports');
    }
};
