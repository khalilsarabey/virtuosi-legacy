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
            $table->string('dep_airline_name')->after('flight_id')->nullable();
            $table->string('dep_airline_code')->after('flight_id')->nullable();
            $table->string('dep_airline_logo')->after('flight_id')->nullable();
            $table->string('ret_airline_name')->after('flight_id')->nullable();
            $table->string('ret_airline_code')->after('flight_id')->nullable();
            $table->string('ret_airline_logo')->nullable();
            $table->string('baggage_allowance')->after('price')->nullable();
            $table->time('dep_arr_time')->after('dep_time')->nullable();
            $table->time('ret_arr_time')->after('ret_dep_time')->nullable();
            $table->string('source')->default('local')->nullable();
            $table->string('connection_id')->nullable();
            $table->string('connection_name')->nullable();
            $table->string('connection_reference')->nullable();
        });
        DB::statement('ALTER TABLE bookings CHANGE created_at created_at TIMESTAMP NULL AFTER check_out');
        DB::statement('ALTER TABLE bookings CHANGE updated_at updated_at TIMESTAMP NULL AFTER created_at');
        DB::statement('ALTER TABLE bookings CHANGE deleted_at deleted_at TIMESTAMP NULL AFTER updated_at');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
        });
    }
};
