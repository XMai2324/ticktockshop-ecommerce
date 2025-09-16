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
    Schema::table('watch_glasses', function (Blueprint $table) {
        $table->integer('quantity')->default(100)->after('price');
    });

    Schema::table('watch_straps', function (Blueprint $table) {
        $table->integer('quantity')->default(100)->after('price');
    });

    Schema::table('watch_boxes', function (Blueprint $table) {
        $table->integer('quantity')->default(100)->after('price');
    });
}

public function down(): void
{
    Schema::table('watch_glasses', function (Blueprint $table) {
        $table->dropColumn('quantity');
    });
    Schema::table('watch_straps', function (Blueprint $table) {
        $table->dropColumn('quantity');
    });
    Schema::table('watch_boxes', function (Blueprint $table) {
        $table->dropColumn('quantity');
    });
}
};
