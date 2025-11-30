<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');

            $table->enum('payment_method', ['cash', 'bank', 'vnpay', 'momo']);
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');

            $table->string('transaction_id')->nullable(); // mã giao dịch từ cổng thanh toán
            $table->integer('amount'); // tổng tiền
            $table->timestamps();

            // Foreign key
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('payments');
    }
};
