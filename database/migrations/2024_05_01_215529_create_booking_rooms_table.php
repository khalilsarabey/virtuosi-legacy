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

        Schema::create('booking_rooms', function (Blueprint $table) {

            $table->id();

            $table->foreignId('room_id')->nullable();
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');

            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('hotel_id')->nullable();

            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('name')->nullable();
            $table->string('hotel')->nullable();
            $table->string('city_code')->nullable();
            $table->date('check_in')->nullable();
            $table->date('check_out')->nullable();
            $table->decimal('price')->nullable();
            $table->text('features')->nullable();
            $table->timestamps();
        });

        Schema::table('bookings', function(Blueprint $table) {
            $table->foreignId('hotel_id')->after('user_id')->nullable();
            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_rooms');
    }
};
