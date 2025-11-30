<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('import_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity_before')->default(0);
            $table->integer('quantity_added')->default(0);
            $table->integer('quantity_after')->default(0);

            $table->decimal('cost_price_before', 12, 2)->default(0);
            $table->decimal('cost_price_after', 12, 2)->default(0);
            $table->decimal('sell_price_after', 12, 2)->default(0);

            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_history');
    }
};
