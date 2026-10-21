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
        Schema::table('rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('rooms', 'active')) {
                $table->boolean('active')->default(1)->nullable();
            } else {
                $table->boolean('active')->default(0)->nullable()->change();
            }
            if (Schema::hasColumn('rooms', 'price')) {
                $table->dropColumn('price');
            }
            $table->unsignedBigInteger('hotel_contract_id')->nullable();
            $table->foreign('hotel_contract_id')->references('id')->on('hotel_contracts')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            //
        });
    }
};
