<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinkDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fk_short_url')->nullable();
            $table->string('client_ip')->nullable();
            $table->string('client_os')->nullable();
            $table->string('client_browser')->nullable();
            $table->string('client_device')->nullable();
            $table->timestamps();

            $table->foreign('fk_short_url')->references('id')->on('short_urls');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('link_details');
    }
}
