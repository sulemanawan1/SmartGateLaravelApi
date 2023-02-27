<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocietybuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('societybuildings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pid');
            $table->foreign('pid')->references('id')->on('phases')->onDelete('cascade');
            $table->string('societybuildingname');

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
        Schema::dropIfExists('societybuildings');
    }
}