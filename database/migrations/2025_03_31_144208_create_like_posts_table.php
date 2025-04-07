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
        Schema::create('like_posts', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['like', 'dislike'])->default('like');
            $table->foreignId('user_id')->constained()->cascadeOnDelete();
            $table->foreignId('post_id')->constained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('like_posts');
    }
};
