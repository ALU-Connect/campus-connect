<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('thread_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->constrained('petition_threads')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('vote_type', 10);
            $table->timestamps();
            $table->unique(['thread_id', 'user_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('thread_votes');
    }
};