<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('price', 15, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Sửa về kiểu cũ nếu trước đó là integer/decimal nhỏ hơn
            $table->decimal('price', 8, 2)->change(); // chỉnh lại cho khớp với schema cũ của bạn
        });
    }
};

