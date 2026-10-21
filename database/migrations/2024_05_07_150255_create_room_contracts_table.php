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
        Schema::create('hotel_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->cascadeOnDelete();
            $table->boolean('show_packages')->nullable()->default(1);
            $table->boolean('show_hotels')->nullable()->default(1);
            $table->decimal('lunch_meal')->nullable()->default(0);
            $table->decimal('dinner_meal')->nullable()->default(0);
            $table->decimal('breakfast_meal')->nullable()->default(0);
            $table->decimal('child_meals')->nullable()->default(0);
            $table->decimal('bo')->nullable()->default(0);
            $table->decimal('bb')->nullable()->default(0);
            $table->decimal('hb')->nullable()->default(0);
            $table->decimal('fb')->nullable()->default(0);
            $table->decimal('ai')->nullable()->default(0);
            $table->decimal('sai')->nullable()->default(0);
            $table->decimal('uai')->nullable()->default(0);
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('room_contracts');
    }
};
