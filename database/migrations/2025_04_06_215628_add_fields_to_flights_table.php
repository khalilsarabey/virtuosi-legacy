<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->integer('business_seats')->after('seats')->default(0);
            $table->integer('package_supplements')->after('combinable_supplements')->default(0)->nullable();
        });
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('cabin')->default('economy');
        });
        Schema::table('prices', function (Blueprint $table) {
            $table->integer('position')->default(1)->nullable();
            $table->string('type')->default('economy');
        });
        DB::table('prices')->where('class', 'L')->update(['position' => 1]);
        DB::table('prices')->where('class', 'M')->update(['position' => 2]);
        DB::table('prices')->where('class', 'Y')->update(['position' => 3]);
        DB::table('prices')->where('class', 'Z')->update(['position' => 1]);
        DB::table('prices')->where('class', 'K')->update(['position' => 2]);
        DB::table('prices')->where('class', 'J')->update(['position' => 3]);
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
