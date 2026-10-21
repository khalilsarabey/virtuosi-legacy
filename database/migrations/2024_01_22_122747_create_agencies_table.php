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
        Schema::create('agencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('domain')->unique('domain');
            $table->string('name');
            $table->unsignedBigInteger('user_id')->nullable()->index('user_id_fk_7805638');
            $table->unsignedSmallInteger('status')->default(0);
            $table->longText('properties')->nullable();
            $table->timestamps();
            $table->string('bg_color')->nullable();
            $table->string('email')->unique('email');
            $table->string('footer_quote')->nullable();
            $table->string('primary_color')->nullable();
            $table->string('secondary_color1')->nullable();
            $table->string('secondary_color2', 7)->nullable();
            $table->integer('balance')->default(0);
            $table->string('permissions')->nullable();
            $table->boolean('oneway')->nullable()->default(true);
            $table->boolean('multiple')->nullable()->default(true);
            $table->boolean('fixed')->nullable()->default(true);
            $table->boolean('roundtrip')->nullable()->default(true);
            $table->boolean('tawk')->default(false);
            $table->string('tawk_api', 125)->nullable();
            $table->string('tawk_widget_id', 25)->nullable();
            $table->text('contact')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();
            $table->text('footer_links')->nullable();
            $table->string('mail_host', 100)->nullable()->default('mail.samadonline.com');
            $table->string('mail_port', 15)->nullable()->default('465');
            $table->string('mail_encryption', 15)->nullable()->default('TLS');
            $table->string('mail_username', 100)->nullable()->default('info@samadonline.com');
            $table->string('mail_password')->nullable()->default('pPul+^1#@38k%x%j');
            $table->string('mail_sender_name', 100)->nullable()->default('Samadonline');
            $table->string('mail_sender_email', 50)->nullable()->default('info@samadonline.com');
            $table->string('mail_driver', 25)->nullable()->default('smtp');
            $table->string('color_footer_bg_light', 7)->nullable()->default('#6c757d');
            $table->string('color_footer_bg', 7)->nullable()->default('#ffffff');
            $table->string('color_footer_icons_bg', 7)->nullable();
            $table->string('color_footer_icons_color', 7)->nullable();
            $table->string('color_body_bg', 7)->nullable()->default('#F0F0F0');
            $table->string('color_topbar_bg', 7)->nullable()->default('#F0F0F0');
            $table->string('color_top_menu', 7)->nullable()->default('#000000');
            $table->string('color_success', 7)->nullable()->default('#869e3e');
            $table->string('color_info', 7)->nullable()->default('#17a2b8');
            $table->string('color_warning', 7)->nullable()->default('#f4d449');
            $table->string('color_danger', 7)->nullable()->default('#c9245f');
            $table->string('color_light', 7)->nullable()->default('#f8f9fa');
            $table->string('color_footer_text', 7)->nullable()->default('#000000');
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_address')->nullable();
            $table->integer('max_logo_height')->nullable()->default(45);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agencies');
    }
};
