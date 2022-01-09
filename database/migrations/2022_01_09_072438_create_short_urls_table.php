<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShortUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('short_urls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fk_user_id');
            $table->foreignId('fk_tag_id');
            $table->string('short_url');
            $table->string('long_url');
            $table->string('link_status')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('fk_user_id')->references('id')->on('users');
            $table->foreign('fk_tag_id')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('short_urls');
    }
}
