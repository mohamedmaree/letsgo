<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type',['people','goods'])->nullable();
            $table->integer('car_type_id')->nullable();
            $table->integer('counter_inside_city')->nullable();
            $table->integer('counter_outside_city')->nullable();
            $table->integer('km_inside_city_single')->nullable();
            $table->integer('km_inside_city_share')->nullable();            
            $table->integer('km_outside_city')->nullable();            
            $table->integer('km_outside_city_from_distance')->nullable();            
            $table->integer('km_outside_city_to_distance')->nullable();            
            $table->integer('waiting_minute_inside')->nullable();            
            $table->integer('min_price_inside')->nullable();            
            $table->integer('min_price_outside')->nullable();            
            $table->integer('client_cancel_inside')->nullable();            
            $table->integer('client_cancel_outside')->nullable(); 
            $table->integer('captain_cancel')->nullable();            
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
        Schema::dropIfExists('prices');
    }
}
