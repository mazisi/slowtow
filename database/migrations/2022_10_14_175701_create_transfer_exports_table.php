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
        Schema::create('transfer_exports', function (Blueprint $table) {
            $table->id();
            $table->string('trading_name');
            $table->string('gau_or_blg_number')->nullable();
            $table->string('province')->nullable();
            $table->string('deposit_invoice')->nullable();
            $table->string('deposit_paid')->nullable();
            $table->string('date_logded')->nullable();
            $table->string('proof_of_lodgement')->nullable();
            $table->string('payment_date')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('finalisation_invoice')->nullable();
            $table->string('finalisation_payment')->nullable();
            $table->string('date_granted')->nullable();
            $table->string('current_status')->nullable();
            $table->longText('notes')->nullable();
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
        Schema::dropIfExists('transfer_exports');
    }
};
