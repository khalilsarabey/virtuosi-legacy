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
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->boolean('searchable')->nullable()->default(false);
            $table->string('type')->nullable()->default('hotel');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
        });
        Schema::table('features', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('features')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('room_features');
    }
};
