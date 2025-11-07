<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('roommate_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user1_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user2_id')->constrained('users')->onDelete('cascade');
            $table->decimal('compatibility_score', 5, 2)->nullable();
            $table->string('status', 50)->default('pending');
            $table->timestamps();
            $table->unique(['user1_id', 'user2_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('roommate_matches');
    }
};