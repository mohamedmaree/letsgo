<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('price_id')->nullable();
            $table->integer('num_persons')->nullable();            
            $table->string('start_address')->nullable();
            $table->double('start_lat')->nullable();
            $table->double('start_long')->nullable();
            $table->string('end_address')->nullable();
            $table->double('end_lat')->nullable();
            $table->double('end_long')->nullable();
            $table->string('expected_price')->nullable();
            $table->string('price')->nullable();
            $table->string('expected_distance')->nullable();
            $table->string('distance')->nullable();
            $table->string('expected_period')->nullable();            
            $table->string('period')->nullable();
            $table->string('total_payments')->nullable();
            $table->enum('confirm_payment',['true','false'])->nullable();
            $table->string('payment_type')->nullable();
            $table->string('shipment_image')->nullable();
            $table->string('identity_type')->nullable();
            $table->string('identity_number')->nullable();
            $table->string('initial_wait')->nullable();
            $table->enum('captain_in_road',['true','false'])->nullable()              
            $table->enum('captain_arriveed',['true','false'])->nullable();              
            $table->enum('start_journey',['true','false'])->nullable(); 
            $table->dateTime('reception_time')->nullable();
            $table->dateTime('captain_arrived_time')->nullable();
            $table->dateTime('start_journey_time')->nullable();
            $table->dateTime('end_journey_time')->nullable();
            $table->enum('status',['wait', 'in_journey', 'finished'])->nullable();           
            $table->enum('join_order',['pending', 'agree', 'refuse'])->nullable();           
            $table->enum('have_coupon',['true','false'])->nullable(); 
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
        Schema::dropIfExists('order_users');
    }
}
