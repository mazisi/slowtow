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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('reg_number');
            $table->string('vat_number')->nullable();
            $table->enum('company_type',
            ["Public Company","Private Company","Close Corporation","Trust",
             "Partnership","Sole Proprietor","Section 21","Non Resident Company","Foreign Company","Association"]);
            $table->string('gatla');
            $table->string('address');
            $table->string('postal_address')->nullable();
            $table->string('website');
            $table->string('email');
            $table->string('email1')->nullable();
            $table->string('email2')->nullable();
            $table->string('tel_number');
            $table->string('tel_number1')->nullable();
            $table->string('fax')->nullable();
            $table->enum('cipc_certificate_status',['One','Two','Three']);
            $table->enum('bee_status',['One','Two']);
            $table->boolean('deactive')->default(1);
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
        Schema::table('companies', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
