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
        Schema::create('posts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('title')->comment('Post title');
            $table->text('content')->comment('Post content')->nullable();
            $table->foreignUlid('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignUlid('updated_by')->constrained('users')->onDelete('cascade');
            $table->unsignedTinyInteger('status')->default(\App\Enums\PostStatus::Draft)->comment('Post status');
            $table->timestamps();
            $table->softDeletes();
            $table->dateTime('published_at')->nullable()->comment('Published at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
