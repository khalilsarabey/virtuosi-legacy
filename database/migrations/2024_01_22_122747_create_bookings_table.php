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
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference')->nullable();
            $table->string('status')->nullable();
            $table->string('type')->nullable();
            $table->integer('infants')->nullable();
            $table->integer('adults')->nullable();
            $table->integer('children')->nullable();
            $table->integer('price')->nullable();
            $table->longText('payload')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('user_id')->nullable()->index('user_fk_7805616');
            $table->unsignedBigInteger('flight_id')->nullable()->index('flight_fk_7805637');
            $table->unsignedBigInteger('dep_flight_id')->nullable()->index('dep_flight_fk_7805638');
            $table->unsignedBigInteger('ret_flight_id')->nullable()->index('ret_flight_fk_7805639');
            $table->string('dep_from_city', 100)->nullable()->index('dep_from_city');
            $table->string('dep_to_city', 100)->nullable()->index('dep_to_city');
            $table->string('ret_from_city', 100)->nullable()->index('ret_from_city');
            $table->string('ret_to_city', 100)->nullable()->index('ret_to_city');
            $table->string('dep_from_code', 100)->nullable();
            $table->string('dep_to_code', 100)->nullable();
            $table->string('ret_from_code', 100)->nullable();
            $table->string('ret_to_code', 100)->nullable();
            $table->string('dep_flight_number', 50)->nullable();
            $table->string('ret_flight_number', 50)->nullable();
            $table->date('dep_date')->nullable();
            $table->time('dep_time')->nullable();
            $table->date('ret_date')->nullable();
            $table->time('ret_time')->nullable();
            $table->time('ret_dep_time')->nullable();
            $table->integer('children_price')->nullable();
            $table->integer('infants_price')->nullable();
            $table->integer('adults_price')->nullable();
            $table->integer('supplements')->nullable();
            $table->integer('total')->nullable();
            $table->bigInteger('agency_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
