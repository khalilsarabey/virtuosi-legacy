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
        Schema::create('turkish_custom_pricings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('turkish_custom_pricings_user_id_foreign');
            $table->unsignedBigInteger('turkish_destination_id')->nullable()->index('turkish_custom_pricings_turkish_destination_id_foreign');
            $table->unsignedBigInteger('turkish_airport_id')->nullable()->index('turkish_custom_pricings_turkish_airport_id_foreign');
            $table->text('agency_remark')->nullable();
            $table->text('fare_remark')->nullable();
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
        Schema::dropIfExists('turkish_custom_pricings');
    }
};
