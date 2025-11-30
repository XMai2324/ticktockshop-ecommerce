<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Copy dữ liệu từ price sang cost_price
        DB::table('products')->update([
            'cost_price' => DB::raw('price')
        ]);
    }

    public function down(): void
    {
        // Nếu rollback, có thể reset cost_price về 0
        DB::table('products')->update(['cost_price' => 0]);
    }
};
