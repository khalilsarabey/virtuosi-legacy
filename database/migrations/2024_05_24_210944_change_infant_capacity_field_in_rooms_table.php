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
            $table->integer('infant_capacity')->default(0)->change();
            $table->integer('child_capacity')->default(0)->change();
            $table->integer('adult_capacity')->default(0)->change();
            $table->dropColumn('adult_enabled');
            $table->dropColumn('child_enabled');
            $table->dropColumn('infant_enabled');
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
