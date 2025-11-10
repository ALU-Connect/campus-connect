<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('meal_swipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recipient_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('status', 50)->default('available');
            $table->timestamps();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamp('used_at')->nullable();
        });
    }
    public function down(): void {
        Schema::dropIfExists('meal_swipes');
    }
};