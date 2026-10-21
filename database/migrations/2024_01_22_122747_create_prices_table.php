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
        Schema::create('prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('class', 2);
            $table->integer('adult');
            $table->integer('child');
            $table->integer('infant');
            $table->integer('seats')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('flight_id')->nullable()->index('flight_fk_7805591');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prices');
    }
};
