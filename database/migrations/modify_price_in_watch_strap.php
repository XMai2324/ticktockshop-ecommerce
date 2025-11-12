<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('watch_straps', function (Blueprint $table) {
        $table->bigInteger('price')->change(); // nếu muốn đổi từ decimal sang bigint
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('watch_strap', function (Blueprint $table) {
                        $table->decimal('price', 10, 2)->change();

        });
    }
};
