<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
  
    public function up(): void
    {
        Schema::create(config('filament-comments.comment_activity_table', 'comment_activities'), function (Blueprint $table) {
            $table->id();
            $table->enum('activity_type', ['liked', 'disliked']);
            $table->foreignId('comment_id')->constrained(config('filament-comments.comment_table', 'comments'), 'id')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained(config('filament-comments.user_table', 'users'), 'id')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }
  
    public function down(): void
    {
        Schema::dropIfExists(config('filament-comments.comment_activity_table', 'comment_activities'));
    }
};