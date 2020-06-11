<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('video_url', 255)->nullable();
            $table->string('thumb_url', 255)->nullable();
            $table->string('url_id', 255)->nullable();
            $table->string('title', 255)->nullable();
            $table->text('caption')->nullable();
            $table->string('contributor', 255)->nullable();
            $table->string('contributor_fee', 255)->nullable();
            $table->string('tags', 255)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('videos');
    }
}
