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
        Schema::table('pricing_airlines', function (Blueprint $table) {
            $table->foreign(['parent_id'])->references(['id'])->on('pricing_airlines');
            $table->foreign(['pricing_category_id'])->references(['id'])->on('pricing_categories')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pricing_airlines', function (Blueprint $table) {
            $table->dropForeign('pricing_airlines_parent_id_foreign');
            $table->dropForeign('pricing_airlines_pricing_category_id_foreign');
        });
    }
};
