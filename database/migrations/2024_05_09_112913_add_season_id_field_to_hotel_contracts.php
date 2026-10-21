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
        Schema::table('hotel_contracts', function (Blueprint $table) {
            $table->unsignedBigInteger('season_id');
            $table->foreign('season_id')->references('id')->on('seasons')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_contracts', function (Blueprint $table) {
            //
        });
    }
};
