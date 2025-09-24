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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('promotion_id')->nullable()
                  ->constrained('promotions')->nullOnDelete();
            $table->string('promotion_code')->nullable()->index(); // Lưu snapshot mã
            $table->decimal('discount_amount', 12, 2)->default(0); // Số tiền đã giảm
            $table->decimal('final_price', 12, 2)->default(0);     // Tổng sau giảm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('promotion_id');
            $table->dropColumn(['promotion_code', 'discount_amount', 'final_price']);
        });
    }
};
