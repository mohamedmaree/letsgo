<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRewardsHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rewards_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable(); 
            $table->integer('num_orders')->nullable(); 
            $table->integer('points')->nullable(); 
            $table->date('date')->nullable();                
            $table->integer('month')->nullable(); 
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
        Schema::dropIfExists('rewards_history');
    }
}
