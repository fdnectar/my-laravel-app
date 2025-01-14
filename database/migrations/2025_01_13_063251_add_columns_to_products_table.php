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
        Schema::table('products', function (Blueprint $table) {
            $table->text('description')->after('product_name');
            $table->integer('stock_quantity')->default(0)->after('product_price');
            $table->integer('low_stock_threshold')->default(5)->after('stock_quantity');
            $table->enum('status', ['active', 'inactive', 'out_of_stock'])->default('active')->after('stock_quantity');
            $table->json('specifications')->nullable()->after('status');
            $table->string('sku')->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
    */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['stock_quantity', 'low_stock_threshold', 'track_inventory', 'status', 'specifications', 'sku']);
        });
    }
};
