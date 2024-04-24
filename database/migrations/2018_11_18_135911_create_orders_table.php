<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('order_type',['order','journey'])->default('order');
            $table->integer('user_id')->nullable();
            $table->integer('captain_id')->nullable();
            $table->integer('car_id')->nullable();
            $table->integer('car_type_id')->nullable();
            $table->integer('price_id')->nullable();
            $table->integer('num_persons')->nullable();            
            $table->enum('type',['now','later'])->default('now');
            $table->enum('cheaper_way',['bids','share'])->nullable();
            $table->string('later_order_date')->nullable();
            $table->string('later_order_time')->nullable();
            $table->string('start_address')->nullable();
            $table->double('start_lat')->nullable();
            $table->double('start_long')->nullable();
            $table->string('end_address')->nullable();
            $table->double('end_lat')->nullable();
            $table->double('end_long')->nullable();
            $table->double('current_lat')->nullable();
            $table->double('current_long')->nullable();            
            $table->string('expected_price')->nullable();
            $table->string('price')->nullable();
            $table->string('total_payments')->nullable();
            $table->enum('confirm_payment',['true','false'])->nullable();
            $table->string('currency_ar')->nullable();
            $table->string('currency_en')->nullable();
            $table->enum('service_in',['mycity','between_cities'])->nullable();           
            $table->enum('service_type',['people','goods'])->nullable();              
            $table->integer('country_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('shipment_image')->nullable();
            $table->string('identity_type')->nullable();
            $table->string('identity_number')->nullable();
            $table->string('expected_distance')->nullable();
            $table->string('expected_period')->nullable();
            $table->string('distance')->nullable();
            $table->string('period')->nullable();
            $table->string('initial_wait')->nullable();
            $table->string('during_order_wait')->nullable();
            $table->enum('status',['open','inprogress','finished','closed'])->nullable();           
            $table->enum('captain_in_road',['true','false'])->nullable()              
            $table->enum('captain_arriveed',['true','false'])->nullable();              
            $table->enum('start_journey',['true','false'])->nullable(); 
            $table->enum('car_complete',['true','false'])->nullable(); 
            $table->dateTime('reception_time')->nullable();
            $table->dateTime('captain_arrived_time')->nullable();
            $table->dateTime('start_journey_time')->nullable();
            $table->dateTime('end_journey_time')->nullable();
            $table->longText('close_reason')->nullable();
            $table->string('year')->nullable();
            $table->integer('month')->nullable();
            $table->integer('hour')->nullable();
            $table->enum('have_coupon',['true','false'])->nullable(); 
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
