<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::create('watch_straps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('material');  // chất liệu dây
            $table->string('color');     // màu sắc
            $table->decimal('price', 10, 2); // giá dây đeo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('watch_straps');
    }
};
