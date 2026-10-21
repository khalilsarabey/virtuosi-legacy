<?php

use App\Models\Agency;
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
        Schema::table('agencies', function (Blueprint $table) {
            $table->text('economy_classes')->nullable();
            $table->text('business_classes')->nullable();
        });
        $agency = Agency::query()->first();
        if($agency) {
            Agency::query()->first()->update(['economy_classes' => ['L', 'M', 'Y'], 'business_classes' => ['Z', 'K', 'J']]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agencies', function (Blueprint $table) {
            //
        });
    }
};
