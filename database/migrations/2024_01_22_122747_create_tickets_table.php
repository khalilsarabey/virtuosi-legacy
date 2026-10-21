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
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('full_name')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->string('phone')->nullable();
            $table->string('nationality');
            $table->integer('price')->nullable();
            $table->string('class', 1)->nullable();
            $table->string('passport_number')->nullable();
            $table->string('passport_expiry')->nullable();
            $table->string('passport_name')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
            $table->softDeletes();
            $table->unsignedBigInteger('booking_id')->nullable()->index('booking_fk_7805651');
            $table->unsignedBigInteger('flight_id')->nullable()->index('flight_fk_7805652');
            $table->string('type', 20)->nullable();
            $table->string('dep_from_city')->nullable();
            $table->string('dep_to_city')->nullable();
            $table->string('dep_from_code', 6)->nullable();
            $table->string('dep_to_code', 6)->nullable();
            $table->date('dep_date')->nullable();
            $table->time('dep_time')->nullable();
            $table->time('dep_arr_time');
            $table->date('ret_date')->nullable();
            $table->time('ret_time')->nullable();
            $table->time('ret_arr_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
