<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('password');
            $table->string('phonekey')->nullable();
            $table->string('phone')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->enum('captain',['true','false'])->default('false');
            $table->enum('captain_type',['saudi','driver'])->nullable();
            $table->integer('plan_id')->default('1');
            $table->string('address')->nullable();
            $table->double('lat')->nullable();
            $table->double('long')->nullable();
            $table->string('avatar')->default('default.png');
            $table->enum('active',['true','false','blocked'])->default('false');           
            $table->integer('role')->default('0');
            $table->string('code')->nullable();
            $table->string('pin_code')->nullable();
            $table->string('share_code')->nullable();
            $table->string('friend_code')->nullable();
            $table->string('friend_code_used')->nullable();
            $table->string('lang')->default('ar');
            $table->enum('service_in',['mycity','between_cities','all'])->default('all');           
            $table->enum('service_type',['people','goods','all'])->default('all');           
            $table->string('num_available_hours')->default('0');
            $table->string('points')->default('0');
            $table->string('balance')->default('0');
            $table->enum('use_balance_first',['true','false'])->default('false');
            $table->string('num_opened_orders')->default('0');
            $table->string('num_done_orders')->default('0');
            $table->string('num_closed_orders')->default('0');
            $table->integer('num_rating')->default('0');
            $table->integer('rating')->default('0');
            $table->integer('num_comments')->default('0');
            $table->integer('captain_current_car_id')->nullable();
            $table->integer('captain_current_car_type_id')->nullable();
            $table->enum('available',['true','false'])->default('false');
            $table->enum('have_order',['true','false'])->default('false');
            $table->rememberToken();
            $table->timestamps();

        });

         // Insert some stuff
        $user = new User;
        $user->first_name = 'اوامر';
        $user->last_name  = ' الشبكه';
        $user->email      = 'aait@info.com';
        $user->password   = bcrypt(123456789);
        $user->phonekey   = '00966';
        $user->phone      = '123456789';
        $user->avatar     = 'default.png';
        $user->active     = 'true';
        $user->role       = '1';
        $user->save();


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
