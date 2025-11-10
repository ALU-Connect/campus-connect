<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('petition_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petition_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('petition_threads')->onDelete('cascade');
            $table->text('content');
            $table->string('thread_type', 50)->default('main');
            $table->integer('upvote_count')->default(0);
            $table->integer('downvote_count')->default(0);
            $table->timestamps();
            $table->index('petition_id');
        });
    }
    public function down(): void {
        Schema::dropIfExists('petition_threads');
    }
};