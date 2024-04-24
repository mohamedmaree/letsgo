<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Html;
class HtmlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('html', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('footer_copyrigh')->nullable();
            $table->string('email_header_color')->nullable();
            $table->string('email_footer_color')->nullable();
            $table->string('email_font_color');
            $table->longText('google_analytics')->nullable();
            $table->longText('live_chat')->nullable();
            $table->timestamps();
        });

        $html = new Html;
        $html->email_font_color = '#000';
        $html->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('html');
    }
}
