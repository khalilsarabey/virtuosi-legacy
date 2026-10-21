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
        Schema::table('airline_bookings', function (Blueprint $table) {
            $table->dateTime('dep_from_date')->after('dep_from')->nullable();
            $table->dateTime('dep_to_date')->after('dep_to')->nullable();
            $table->dateTime('ret_from_date')->after('ret_from')->nullable();
            $table->dateTime('ret_to_date')->after('ret_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('airline_bookings', function (Blueprint $table) {
            //
        });
    }
};
