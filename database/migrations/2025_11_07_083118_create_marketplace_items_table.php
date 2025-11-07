<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('marketplace_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('category', 100)->nullable();
            $table->string('condition', 50)->nullable();
            $table->string('status', 50)->default('available');
            $table->json('images')->nullable();
            $table->timestamps();
            $table->index('status');
            $table->index('category');
        });
    }
    public function down(): void {
        Schema::dropIfExists('marketplace_items');
    }
};