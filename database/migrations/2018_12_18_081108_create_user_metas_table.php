<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_meta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('fullname')->nullable();
            $table->string('identity_card')->nullable();
            $table->string('driving_license')->nullable();
            $table->string('car_form')->nullable();
            $table->string('iban')->nullable();
            $table->string('car_insurance')->nullable();
            $table->string('personal_image')->nullable();
            $table->string('car_type')->nullable();
            $table->string('car_model')->nullable();
            $table->string('manufacturing_year')->nullable();
            $table->string('car_number')->nullable();
            $table->string('car_image')->nullable();
            $table->string('job_status')->nullable();
            $table->enum('status',['agree','refused','pending'])->nullable();
            $table->enum('seen',['true','false'])->nullable();
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
        Schema::dropIfExists('user_meta');
    }
}
