<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Связь с заказом
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Связь с товаром
            $table->integer('quantity'); // Количество данного товара в заказе
            $table->decimal('price_per_unit', 10, 2); // Цена товара на момент покупки
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};