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
        Schema::create('hotel_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->nullable()->constrained();
            $table->integer('position')->default(1);
            $table->string('title');
            $table->boolean('status')->default(1);
            $table->integer('adult')->nullable()->default(0);
            $table->integer('child')->nullable()->default(0);
            $table->integer('infant')->nullable()->default(0);
            $table->string('duration_type')->nullable();
            $table->unsignedInteger('duration')->nullable();
            $table->string('applicable_to')->default('room');
            $table->string('applicable_as')->default('percentage');
            $table->string('destination')->nullable();
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
        Schema::dropIfExists('hotel_rules');
    }
};
