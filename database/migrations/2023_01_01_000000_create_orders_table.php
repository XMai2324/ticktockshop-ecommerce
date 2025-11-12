<?php
// database/migrations/2023_01_01_000000_create_orders_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('phone');
            $table->string('email');
            $table->string('province');
            $table->string('district');
            $table->string('address');
            $table->enum('payment_method', ['cash','bank']);
            $table->integer('subtotal');
            $table->integer('shipping')->default(0);
            $table->integer('discount')->default(0);
            $table->integer('grand_total');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('orders');
    }
};


?>