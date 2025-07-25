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
        Schema::create('watch_straps', function (Blueprint $table) {

        $table->id();
        $table->string('name');
        $table->string('material')->nullable(); // ví dụ: da, kim loại, cao su
        $table->string('image')->nullable();
        $table->decimal('price', 10, 2)->nullable();
        $table->unsignedBigInteger('product_id')->nullable(); // nếu liên kết sản phẩm
        $table->timestamps();

        $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watch_straps');
    }
};
