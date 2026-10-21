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
        Schema::create('turkish_airports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 10);
            $table->unsignedBigInteger('turkish_destination_id')->index('turkish_airports_turkish_destination_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('turkish_airports');
    }
};
