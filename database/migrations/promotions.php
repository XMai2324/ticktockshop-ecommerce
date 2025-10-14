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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('code')->unique();                 // Mã KM khi khách nhập
            $table->enum('type', ['percent', 'fixed']);       // percent: theo %, fixed: trừ thẳng tiền
            $table->decimal('value', 12, 2);                  // Ví dụ 10 nghĩa là 10%, hoặc 50000 VND
            $table->decimal('max_discount', 12, 2)->nullable();// Giới hạn giảm tối đa cho mã %
            $table->decimal('min_order_value', 12, 2)->default(0);
            $table->unsignedInteger('usage_limit')->nullable();    // Tổng lượt dùng toàn hệ thống
            $table->unsignedInteger('per_user_limit')->nullable(); // Lượt tối đa mỗi user
            $table->unsignedInteger('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
