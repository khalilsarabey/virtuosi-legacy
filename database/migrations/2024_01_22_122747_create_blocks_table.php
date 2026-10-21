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
        Schema::create('blocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('position')->nullable();
            $table->string('link')->nullable();
            $table->string('layout')->nullable();
            $table->boolean('hide_title')->nullable()->default(false);
            $table->timestamps();
            $table->unsignedBigInteger('parent_id')->nullable()->index('parent_fk_8239207');
            $table->unsignedBigInteger('agency_id')->nullable()->index('team_fk_8239206');
            $table->boolean('active')->nullable()->default(false)->index('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blocks');
    }
};
