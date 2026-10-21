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
        Schema::table('room_prices', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('ai_price');
            $table->dropColumn('bb_price');
            $table->decimal('adult_single')->nullable();
            $table->decimal('adult_double')->nullable();
            $table->decimal('adult_extra_bed')->nullable();
            $table->decimal('child_double')->nullable();
            $table->decimal('child_first_extra_bed')->nullable();
            $table->decimal('child_second_extra_bed')->nullable();
            $table->decimal('infant')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('room_prices', function (Blueprint $table) {
            //
        });
    }
};
