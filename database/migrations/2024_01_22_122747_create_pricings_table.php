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
        Schema::create('pricings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedDecimal('selling_commission')->nullable()->default(0);
            $table->unsignedDecimal('selling_service_fee')->nullable()->default(0);
            $table->decimal('selling_fixed_fee')->nullable()->default(0);
            $table->unsignedDecimal('net_commission')->nullable()->default(0);
            $table->unsignedDecimal('net_service_fee')->nullable()->default(0);
            $table->text('agency_remark')->nullable();
            $table->text('fare_remark')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index('pricings_user_id_foreign');
            $table->unsignedBigInteger('pricing_airline_id')->nullable()->index('pricings_pricing_airline_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pricings');
    }
};
