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
            $table->string('address')->nullable();
            $table->string('business_address_postal_code')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('postal_address_code')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('email1')->nullable();
            $table->string('email2')->nullable();
            $table->string('tel_number')->nullable();
            $table->string('tel_number1')->nullable();
            $table->string('fax')->nullable();
            $table->boolean('active')->default(1);
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
        Schema::table('companies', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
