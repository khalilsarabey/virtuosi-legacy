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
        Schema::create('pricing_airlines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('tour_code')->nullable();
            $table->text('agency_remark')->nullable();
            $table->text('fare_remark')->nullable();
            $table->unsignedInteger('has_children')->nullable()->default(0);
            $table->unsignedInteger('is_child')->nullable()->default(0);
            $table->unsignedBigInteger('parent_id')->nullable()->index('pricing_airlines_parent_id_foreign');
            $table->timestamps();
            $table->softDeletes();
            $table->decimal('default_selling_commission')->nullable()->default(0);
            $table->decimal('default_selling_service_fee')->nullable()->default(0);
            $table->decimal('default_selling_fixed_fee')->nullable()->default(0);
            $table->decimal('default_net_commission')->nullable()->default(0);
            $table->decimal('default_net_service_fee')->nullable()->default(0);
            $table->unsignedBigInteger('pricing_category_id')->nullable()->index('pricing_airlines_pricing_category_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pricing_airlines');
    }
};
