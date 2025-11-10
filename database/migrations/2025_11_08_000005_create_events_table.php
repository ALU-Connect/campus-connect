<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petition_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('start_at');
            $table->timestamp('end_at')->nullable();
            $table->string('location')->nullable();
            $table->string('status', 50)->default('upcoming');
            $table->string('image_path')->nullable();
            $table->integer('max_attendees')->nullable();
            $table->timestamps();
            $table->index('start_at');
        });
    }
    public function down(): void {
        Schema::dropIfExists('events');
    }
};