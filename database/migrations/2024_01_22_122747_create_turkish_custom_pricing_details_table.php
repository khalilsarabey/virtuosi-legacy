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
        Schema::create('turkish_custom_pricing_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('turkish_custom_pricing_id')->index('turkish_custom_pricing_details_turkish_custom_pricing_id_foreign');
            $table->decimal('selling_commission');
            $table->decimal('selling_service_fee');
            $table->decimal('selling_fixed_fee');
            $table->decimal('net_commission');
            $table->decimal('net_service_fee');
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
        Schema::dropIfExists('turkish_custom_pricing_details');
    }
};
