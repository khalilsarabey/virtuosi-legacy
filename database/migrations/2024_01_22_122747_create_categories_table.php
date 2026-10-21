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
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->bigInteger('section_id')->nullable();
            $table->string('link')->nullable();
            $table->boolean('active')->nullable()->default(false);
            $table->text('permissions')->nullable();
            $table->text('agencies_permissions')->nullable();
            $table->string('whitelabel_permissions', 1000)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('slug')->unique();
            $table->integer('position')->nullable()->default(0);
            $table->boolean('show_title')->default(true);
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
        Schema::dropIfExists('categories');
    }
};
