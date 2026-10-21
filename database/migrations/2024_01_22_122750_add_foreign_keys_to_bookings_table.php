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
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreign(['dep_flight_id'], 'dep_flight_fk_7805638')->references(['id'])->on('flights');
            $table->foreign(['ret_flight_id'], 'ret_flight_fk_7805639')->references(['id'])->on('flights');
            $table->foreign(['flight_id'], 'flight_fk_7805637')->references(['id'])->on('flights');
            $table->foreign(['user_id'], 'user_fk_7805616')->references(['id'])->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign('dep_flight_fk_7805638');
            $table->dropForeign('ret_flight_fk_7805639');
            $table->dropForeign('flight_fk_7805637');
            $table->dropForeign('user_fk_7805616');
        });
    }
};
