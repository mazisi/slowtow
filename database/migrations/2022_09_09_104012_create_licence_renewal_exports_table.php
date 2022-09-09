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
        Schema::create('licence_renewal_exports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licence_renewal_id')->constrained()->onDelete('cascade');
            $table->string('is_active');
            $table->string('trading_name');
            $table->string('licence_number');
            $table->string('renewal_date');
            $table->string('renewal_amount')->nullable();
            $table->string('is_quoted')->nullable();
            $table->string('is_quote_sent')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('invoice_number')->nullable();
            $table->date('payment_to_liquour_board')->nullable();
            $table->string('renewal_granted')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('proof_of_delivery')->nullable();
            $table->string('notes')->nullable();
            // $table->timestamps();
        });
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licence_renewal_exports');
    }
};
