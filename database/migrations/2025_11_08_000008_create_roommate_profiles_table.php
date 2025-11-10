<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('roommate_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->text('bio')->nullable();
            $table->integer('cleanliness_level')->nullable();
            $table->integer('noise_tolerance')->nullable();
            $table->string('sleep_schedule', 50)->nullable();
            $table->string('study_preference', 50)->nullable();
            $table->string('guest_policy', 50)->nullable();
            $table->boolean('smoking')->default(false);
            $table->json('preferences')->nullable();
            $table->string('status', 50)->default('not_looking');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('roommate_profiles');
    }
};