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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->dateTime('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('locale')->nullable();
            $table->string('type')->nullable();
            $table->integer('restricted')->nullable();
            $table->unsignedDecimal('balance', 15)->nullable();
            $table->integer('is_approved')->nullable();
            $table->integer('active')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('agency_id')->nullable();
            $table->boolean('api')->nullable()->default(false);
            $table->unsignedBigInteger('pricing_class_id')->nullable()->index('users_pricing_class_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
