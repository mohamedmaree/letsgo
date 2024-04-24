<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaptainMoneyHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('captain_money_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('captain_id')->nullable(); 
            $table->enum('type',['pay','receive'])->nullable();                
            $table->string('amount')->nullable();                
            $table->string('currency')->nullable();                
            $table->dateTime('start_date')->nullable();                
            $table->dateTime('end_date')->nullable();                             
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
        Schema::dropIfExists('captain_money_histories');
    }
}
