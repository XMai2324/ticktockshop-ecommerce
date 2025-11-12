<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('watch_glasses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('material')->nullable(); // Ví dụ: kính sapphire, kính khoáng
            $table->string('color')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('watch_glasses');
    }
};
