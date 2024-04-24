<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\SmsEmailNotification;

class SmsAndEmailAndNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smsemailnotification', function (Blueprint $table) {
//            $table->increments('id');
//            $table->string('smtp_type');
//            $table->string('smtp_username')->nullable();
//            $table->string('smtp_password')->nullable();
//            $table->string('smtp_sender_email')->nullable();
//            $table->string('smtp_sender_name')->nullable();
//            $table->integer('smtp_port')->nullable();
//            $table->string('smtp_host')->nullable();
//            $table->string('smtp_encryption')->nullable();
//            $table->string('sms_number')->nullable();
//            $table->string('sms_password')->nullable();
//            $table->string('sms_sender_name')->nullable();
//            $table->string('oneSignal_application_id')->nullable();
//            $table->string('oneSignal_authorization')->nullable();
//            $table->string('fcm_server_key')->nullable();
//            $table->string('fcm_sender_id')->nullable();
//            $table->timestamps();

            $table->increments('id');
            $table->string('type');
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('sender_email')->nullable();
            $table->string('sender_name')->nullable();
            $table->integer('port')->nullable();
            $table->string('host')->nullable();
            $table->string('encryption')->nullable();
            $table->string('number')->nullable();
            $table->string('application_id')->nullable();
            $table->string('authorization')->nullable();
            $table->string('server_key')->nullable();
            $table->string('sender_id')->nullable();
            $table->enum('active',['true','false'])->nullable();
            $table->timestamps();

    });

        $sms = new SmsEmailNotification;
        $sms->type = 'smtp';
        $sms->username = '';
        $sms->password = '';
        $sms->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('smsemailnotification');
    }
}
