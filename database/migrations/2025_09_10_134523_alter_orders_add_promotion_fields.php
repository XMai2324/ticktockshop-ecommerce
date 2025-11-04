<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nếu chưa có bảng orders thì bỏ qua migration này
        if (!Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'promotion_id')) {
                $table->unsignedBigInteger('promotion_id')->nullable()->after('id'); // chỉnh after() cho phù hợp

                // Nếu có bảng promotions và bạn muốn ràng buộc:
                // $table->foreign('promotion_id')->references('id')->on('promotions')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'promotion_id')) {
                // Nếu đã tạo foreign key ở up(), drop FK trước:
                // $table->dropForeign(['promotion_id']);
                $table->dropColumn('promotion_id');
            }
        });
    }
};
