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
            $table->renameColumn('adult_single', 'adult_1');
            $table->renameColumn('adult_double', 'adult_2');
            $table->renameColumn('adult_extra_bed', 'adult_3');
            $table->decimal('adult_4')->nullable();
            $table->decimal('child_1')->nullable();
            $table->decimal('child_2')->nullable();
            $table->decimal('child_3')->nullable();
            $table->decimal('child_4')->nullable();
            $table->decimal('infant_1')->nullable();
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
