<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('movement')->nullable();          // Máy
            $table->string('case_material')->nullable();     // Chất liệu vỏ
            $table->string('strap_material')->nullable();    // Chất liệu dây
            $table->string('glass_material')->nullable();    // Chất liệu kính
            $table->string('diameter')->nullable();          // Đường kính mặt
            $table->string('water_resistance')->nullable();  // Độ chịu nước
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'movement',
                'case_material',
                'strap_material',
                'glass_material',
                'diameter',
                'water_resistance',
            ]);
        });
    }
};
