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
        Schema::create('language_contents', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('content_id');
            $table->unsignedBigInteger('language_id');

            $table->string('name');
            $table->text('description')->nullable();
            $table->string('thumb_url')->nullable();
            $table->string('banner_url')->nullable();
            $table->string('file_path')->nullable();
            $table->string('remote_file_path')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('language_contents');
    }
};
