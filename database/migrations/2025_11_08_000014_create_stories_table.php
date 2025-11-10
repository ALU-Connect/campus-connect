<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('content_type', 50);
            $table->string('content_path')->nullable();
            $table->text('caption')->nullable();
            $table->timestamp('expires_at');
            $table->timestamps();
            $table->index('expires_at');
        });
    }
    public function down(): void {
        Schema::dropIfExists('stories');
    }
};