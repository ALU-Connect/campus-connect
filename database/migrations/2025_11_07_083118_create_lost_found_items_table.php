<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lost_found_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
            $table->string('item_type', 50);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('image_path')->nullable();
            $table->string('status', 50)->default('open');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('lost_found_items');
    }
};