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
        Schema::table('pricing_class_details', function (Blueprint $table) {
            $table->foreign(['pricing_airline_id'])->references(['id'])->on('pricing_airlines')->onDelete('CASCADE');
            $table->foreign(['pricing_class_id'])->references(['id'])->on('pricing_classes')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pricing_class_details', function (Blueprint $table) {
            $table->dropForeign('pricing_class_details_pricing_airline_id_foreign');
            $table->dropForeign('pricing_class_details_pricing_class_id_foreign');
        });
    }
};
