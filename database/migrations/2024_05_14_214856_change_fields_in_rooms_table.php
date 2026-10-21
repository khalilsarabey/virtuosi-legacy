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
            $table->dropColumn('max_capacity');
            $table->dropForeign(['hotel_contract_id']);
            $table->dropColumn('max_extra_beds');
            $table->boolean('adult_enabled')->default(true);
            $table->string('adult_pricing')->nullable();
            $table->integer('adult_capacity')->nullable();
            $table->integer('adult_extra_beds')->nullable();
            $table->boolean('child_enabled')->default(false);
            $table->string('child_pricing')->nullable();
            $table->integer('child_capacity')->nullable();
            $table->integer('child_extra_beds')->nullable();
            $table->boolean('infant_enabled')->default(false);
            $table->string('infant_pricing')->nullable();
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
