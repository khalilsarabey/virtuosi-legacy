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
            $table->index('active');
            $table->index('start_date');
            $table->index('end_date');
            $table->index('adult_capacity');
            $table->index('child_capacity');
            $table->index('infant_capacity');
            if (Schema::hasColumn('rooms', 'combination')) {
                Schema::table('rooms', function (Blueprint $table) {
                    $table->dropColumn('combination');
                });
            }
        });
        Schema::table('airports', function (Blueprint $table) {
            $table->index(['airport_code']);
        });
        Schema::table('hotels', function (Blueprint $table) {
            $table->index(['active', 'location']);
        });
        Schema::table('room_prices', function (Blueprint $table) {
            $table->dropColumn('child_double');
            $table->dropColumn('child_first_extra_bed');
            $table->dropColumn('child_second_extra_bed');
        });
        Schema::table('room_types', function (Blueprint $table) {
            $table->dropColumn('capacity');
            $table->index('active');
        });
        if (Schema::hasTable('room_specifications')) {
            Schema::dropIfExists('room_specifications');
        }
        if (Schema::hasTable('hotel_specifications')) {
            Schema::dropIfExists('hotel_specifications');
        }
        Schema::table('seasons', function (Blueprint $table) {
            $table->index('active');
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
