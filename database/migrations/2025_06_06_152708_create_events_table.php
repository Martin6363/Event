<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['moderation', 'published', 'closed'])->default('moderation');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->date('date');
            $table->time('time');
            $table->integer('duration')->default(1);
            $table->enum('duration_unit', ['hours', 'days']);
            $table->enum('gender', ['male', 'female', 'both'])->nullable();
            $table->integer('members_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
