<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('author_id');
            $table->string('title', 144);
            $table->string('description', 64)->nullable();
            $table->text('text');

            $table->string('cover_image_filename', 255)->nullable();
            $table->string('cover_image_hashname', 255)->nullable();

            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
