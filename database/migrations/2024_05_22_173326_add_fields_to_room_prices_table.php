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
            $table->decimal('infant_6')->nullable()->after('infant_1');
            $table->decimal('infant_5')->nullable()->after('infant_1');
            $table->decimal('infant_4')->nullable()->after('infant_1');
            $table->decimal('infant_3')->nullable()->after('infant_1');
            $table->decimal('infant_2')->nullable()->after('infant_1');

            $table->decimal('adult_6')->nullable()->after('adult_1');
            $table->decimal('adult_5')->nullable()->after('adult_1');

            $table->decimal('child_6')->nullable()->after('child_1');
            $table->decimal('child_5')->nullable()->after('child_1');

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
