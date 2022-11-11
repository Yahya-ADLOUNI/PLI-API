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
        Schema::create('artwork_interest', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('interest_id');
            $table->unsignedBigInteger('artwork_id');
            $table->foreign('interest_id')->on('interests')->references('id')->onDelete('cascade');
            $table->foreign('artwork_id')->on('artworks')->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('artwork_interest');
    }
};
