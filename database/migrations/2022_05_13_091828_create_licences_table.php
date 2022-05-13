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
            $table->string('address');
            $table->string('current_trading_name');
            $table->string('district');
            $table->string('licence_type');
            $table->string('liqour_licence_number')->nullable();
            $table->string('province')->nullable();
            $table->string('reference_number');
            $table->string('magistrate')->nullable();
            $table->string('municipality')->nullable();
            $table->string('licence_date')->nullable();
            $table->string('gtla_expire_date')->nullable();
            $table->string('old_liqour_licence_number');
            $table->string('liquor_board');
            $table->string('must_renew');
            $table->string('active');
            $table->enum('status',['One','Two','Three']);
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
