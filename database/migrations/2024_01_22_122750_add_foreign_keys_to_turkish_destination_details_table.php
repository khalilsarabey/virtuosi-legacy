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
        Schema::table('turkish_destination_details', function (Blueprint $table) {
            $table->foreign(['turkish_destination_id'])->references(['id'])->on('turkish_destinations')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('turkish_destination_details', function (Blueprint $table) {
            $table->dropForeign('turkish_destination_details_turkish_destination_id_foreign');
        });
    }
};
