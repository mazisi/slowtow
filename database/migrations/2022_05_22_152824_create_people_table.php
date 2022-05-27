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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('active')->nullable();
            $table->string('initials')->nullable();
            $table->string('surname');
            $table->string('date_of_birth')->nullable();
            $table->string('id_number')->nullable();
            $table->enum('id_or_passport',['i_d','passport']);
            $table->string('identity_number')->unique()->nullable();
            $table->string('passport')->unique()->nullable();
            $table->string('email_address_1')->nullable();
            $table->string('email_adddress_2')->nullable();
            $table->string('cell_number')->nullable();
            $table->string('fax_number')->nullable();
            $table->string('telephone')->nullable();
            $table->string('home_address')->nullable();
            $table->string('home_address_postal_code')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('work_address')->nullable();
            $table->string('work_address_postal_code')->nullable();
            $table->date('passport_valid_until')->nullable();
            $table->enum('valid_saps_clearance',['yes','no','requested']);
            $table->enum('valid_certified_id',['yes','no','requested']);
            $table->date('saps_clearance_valid_until')->nullable();
            $table->enum('valid_fingerprint',['yes','no','requested']);
            $table->date('valid_fingerprint_valid_until')->nullable();
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
        Schema::table('people', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
