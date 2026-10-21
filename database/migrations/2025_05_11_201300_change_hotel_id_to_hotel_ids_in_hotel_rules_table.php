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
        Schema::table('hotel_rules', function (Blueprint $table) {
            $table->dropForeign(['hotel_id']);
            $table->dropColumn('hotel_id');
            $table->json('hotel_ids')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_rules', function (Blueprint $table) {
            $table->unsignedBigInteger('hotel_id')->nullable()->after('id');
            $table->foreign('hotel_id')->references('id')->on('hotels');
            $table->dropColumn('hotel_ids');
        });
    }
};
