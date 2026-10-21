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
        Schema::create('sections', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();
            $table->string('name', 50);
            $table->string('url')->nullable();
            $table->string('type', 20);
            $table->integer('position')->nullable()->default(1);
            $table->tinyInteger('active')->nullable();
            $table->text('permissions')->nullable();
            $table->text('agencies_permissions')->nullable();
            $table->string('whitelabel_permissions', 1000)->nullable();
            $table->integer('layout')->default(3);
            $table->bigInteger('agency_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sections');
    }
};
