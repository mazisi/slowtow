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
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('people_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('liquor_licence_number')->nullable();
            $table->string('active')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('client_paid_at')->nullable();
            $table->date('payment_to_liquor_board_at')->nullable();
            $table->date('logded_at')->nullable();
            $table->date('issued_at')->nullable();
            $table->date('delivered_at')->nullable();
            $table->date('company_merged_document')->nullable();
            $table->date('individual_merged_document')->nullable();
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
