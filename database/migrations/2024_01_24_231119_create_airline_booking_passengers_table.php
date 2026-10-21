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
        Schema::create('airline_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('integration_id');
            $table->foreign('integration_id')->references('id')->on('integrations')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('airline');
            $table->string('dep_from');
            $table->string('dep_to');
            $table->string('ret_from')->nullable();
            $table->string('ret_to')->nullable();
            $table->decimal('amount')->default(0)->nullable();
            $table->decimal('amount_with_markup')->default(0)->nullable();
            $table->decimal('taxes')->default(0)->nullable();
            $table->decimal('total')->default(0)->nullable();
            $table->decimal('markup')->default(0)->nullable();
            $table->decimal('markup_type')->default(0)->nullable();
            $table->mediumText('payload')->nullable();
            $table->mediumText('request')->nullable();
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
        Schema::dropIfExists('airline_booking_passengers');
    }
};
