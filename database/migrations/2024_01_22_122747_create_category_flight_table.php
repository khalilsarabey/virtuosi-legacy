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
        Schema::create('category_flight', function (Blueprint $table) {
            $table->unsignedBigInteger('flight_id')->index('flight_id_fk_7804146');
            $table->unsignedBigInteger('category_id')->index('category_id_fk_7804146');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_flight');
    }
};
