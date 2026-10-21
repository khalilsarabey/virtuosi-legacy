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
        Schema::table('turkish_custom_pricing_details', function (Blueprint $table) {
            $table->foreign(['turkish_custom_pricing_id'])->references(['id'])->on('turkish_custom_pricings')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('turkish_custom_pricing_details', function (Blueprint $table) {
            $table->dropForeign('turkish_custom_pricing_details_turkish_custom_pricing_id_foreign');
        });
    }
};
