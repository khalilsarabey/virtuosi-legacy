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
        Schema::table('turkish_custom_pricings', function (Blueprint $table) {
            $table->foreign(['turkish_airport_id'])->references(['id'])->on('turkish_airports');
            $table->foreign(['user_id'])->references(['id'])->on('users');
            $table->foreign(['turkish_destination_id'])->references(['id'])->on('turkish_destinations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('turkish_custom_pricings', function (Blueprint $table) {
            $table->dropForeign('turkish_custom_pricings_turkish_airport_id_foreign');
            $table->dropForeign('turkish_custom_pricings_user_id_foreign');
            $table->dropForeign('turkish_custom_pricings_turkish_destination_id_foreign');
        });
    }
};
