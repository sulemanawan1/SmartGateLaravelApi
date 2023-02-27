<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measurements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subadminid');
            $table->foreign('subadminid')->references('id')->on('users')->onDelete('cascade');
            $table->string('type');
            $table->string('unit');
            $table->double('charges');
            $table->double('chargesafterduedate');
            $table->double('appcharges');
            $table->double('tax');
            $table->double('area');
            $table->integer('bedrooms');
            $table->integer('status');
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
        Schema::dropIfExists('measurements');
    }
}
