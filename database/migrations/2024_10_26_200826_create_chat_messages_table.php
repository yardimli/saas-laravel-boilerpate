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
	    Schema::create('chat_messages', function (Blueprint $table) {
		    $table->id();
		    $table->integer('session_id')->index();
		    $table->enum('role', ['user', 'assistant', 'system']);
		    $table->text('message');
		    $table->string('llm')->nullable();
		    $table->integer('prompt_tokens')->nullable();
		    $table->integer('completion_tokens')->nullable();
		    $table->timestamps();
	    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
