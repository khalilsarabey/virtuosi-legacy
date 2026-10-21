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
        Schema::create('pricing_class_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pricing_class_id')->index('pricing_class_details_pricing_class_id_foreign');
            $table->unsignedBigInteger('pricing_airline_id')->index('pricing_class_details_pricing_airline_id_foreign');
            $table->decimal('selling_commission')->nullable()->default(0);
            $table->decimal('selling_service_fee')->nullable()->default(0);
            $table->decimal('selling_fixed_fee')->nullable()->default(0);
            $table->decimal('net_commission')->nullable()->default(0);
            $table->decimal('net_service_fee')->nullable()->default(0);
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
        Schema::dropIfExists('pricing_class_details');
    }
};
