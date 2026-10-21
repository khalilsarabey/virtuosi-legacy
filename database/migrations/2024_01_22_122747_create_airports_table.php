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
        Schema::create('airports', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('airport_code', 4)->nullable();
            $table->string('airport', 50)->nullable();
            $table->string('city_code', 8)->nullable()->index('city_code');
            $table->string('city', 50)->nullable();
            $table->string('country_code', 11)->nullable()->index('country_code');
            $table->string('country', 50)->nullable();
            $table->string('timezone', 8)->nullable();
            $table->string('lat', 9)->nullable();
            $table->string('lon', 10)->nullable();
            $table->boolean('active')->nullable()->default(false);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('airports');
    }
};
