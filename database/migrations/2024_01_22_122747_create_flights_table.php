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
        Schema::create('flights', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('airline_id');
            $table->date('dep_date')->nullable();
            $table->time('dep_time');
            $table->time('dep_arr_time');
            $table->string('dep_flight_number', 50);
            $table->date('ret_dep_date')->nullable();
            $table->time('ret_dep_time')->nullable();
            $table->time('ret_arr_time')->nullable();
            $table->string('ret_flight_number', 50)->nullable();
            $table->string('baggage_allowance')->nullable();
            $table->integer('active')->nullable();
            $table->string('type')->nullable();
            $table->integer('oneway_supplements')->nullable();
            $table->integer('multi_supplements')->nullable();
            $table->integer('seats')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('dep_from_code', 4)->nullable();
            $table->string('dep_to_code', 4)->nullable();
            $table->string('ret_from_code', 4)->nullable();
            $table->string('ret_to_code', 4)->nullable();
            $table->unsignedBigInteger('contract_id')->nullable()->index('contract_fk_7805535');
            $table->text('agencies_permissions')->nullable();
            $table->text('whitelabel_permissions')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('remaining_seats_display')->default(0);
            $table->unsignedSmallInteger('onhold_bookings')->default(0);
            $table->string('categories')->nullable();
            $table->bigInteger('agency_id');
            $table->text('permissions')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flights');
    }
};
