<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Thêm cột is_active + sửa ENUM role của bảng users
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Thêm cột is_active nếu chưa tồn tại
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }
        });

        // sửa ENUM role: từ ['customers','admin'] -> ['user','admin']
        DB::statement("ALTER TABLE users MODIFY role ENUM('user','admin') DEFAULT 'user'");
    }

    /**
     * Rollback về trạng thái trước đây (nếu cần)
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });

        // Khôi phục ENUM cũ
        DB::statement("ALTER TABLE users MODIFY role ENUM('customers','admin') DEFAULT 'customers'");
    }
};
