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
        Schema::table('hotels', function (Blueprint $table) {
            $table->decimal('bb')->nullable();
            $table->decimal('fb')->nullable();
            $table->decimal('ai')->nullable();
            $table->decimal('sai')->nullable();
            $table->decimal('uai')->nullable();
            $table->decimal('breakfast_meal')->nullable();
            $table->decimal('lunch_meal')->nullable();
            $table->decimal('dinner_meal')->nullable();
            $table->decimal('child_meal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotels', function (Blueprint $table) {
            //
        });
    }
};
