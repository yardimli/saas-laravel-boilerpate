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
	    Schema::create('image_gens', function (Blueprint $table) {
		    $table->id();
		    $table->string('session_id')->unique();
		    $table->integer('user_id')->index();
		    $table->text('user_prompt');
		    $table->text('llm_prompt');
		    $table->text('image_prompt');
		    $table->string('image_path')->nullable();
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
        Schema::dropIfExists('image_gens');
    }
};
