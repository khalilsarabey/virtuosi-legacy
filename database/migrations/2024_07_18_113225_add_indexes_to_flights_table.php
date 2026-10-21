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
        Schema::table('flights', function (Blueprint $table) {
            $table->date('dep_date')->nullable()->index()->change();
            $table->date('ret_dep_date')->nullable()->index()->change();
            $table->date('expiry_date')->nullable()->index()->change();
            $table->string('dep_flight_number')->nullable()->index()->change();
            $table->boolean('active')->nullable()->index()->change();
            $table->string('type')->nullable()->index()->change();
            $table->integer('seats')->nullable()->index()->change();
            $table->string('dep_from_code', 4)->nullable()->index()->change();
            $table->string('dep_to_code', 4)->nullable()->index()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flights', function (Blueprint $table) {
            //
        });
    }
};
