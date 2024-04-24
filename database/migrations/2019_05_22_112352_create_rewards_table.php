<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rewards', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type',['client','captain'])->nullable();            
            $table->date('from_date')->nullable();            
            $table->time('from_time')->nullable(); 
            $table->date('to_date')->nullable();            
            $table->time('to_time')->nullable();             
            $table->integer('num_orders')->nullable(); 
            $table->integer('points')->nullable(); 
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
        Schema::dropIfExists('rewards');
    }
}
