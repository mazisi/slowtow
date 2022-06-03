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
        Schema::create('licences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('trading_name');
            $table->string('licence_number')->nullable();
            $table->string('old_licence_number')->nullable();
            $table->string('licence_type');
            $table->date('licence_date');
            $table->date('licence_expire_date')->nullable();
            $table->string('file_number')->nullable();
            $table->string('account_number')->nullable();
            $table->string('address')->nullable();
            $table->string('province')->nullable();
            $table->string('consultant_name')->nullable();
            $table->string('must_renew')->nullable();
            $table->string('licence_status')->nullable();
            $table->text('notes')->nullable();
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
        Schema::table('licences', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
    
};
