<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->blob('image_path')->nullable(); // Add image path
            $table->string('topic')->nullable();       // Add topic
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
}
