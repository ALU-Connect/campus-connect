<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('study_groups', function (Blueprint $table) {
            $table->id();
            $table->string('course_code', 50);
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('meeting_time')->nullable();
            $table->string('location')->nullable();
            $table->integer('max_members')->default(10);
            $table->string('status', 50)->default('active');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('study_groups');
    }
};